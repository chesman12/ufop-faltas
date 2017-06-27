<?php

namespace App\Http\Controllers;

use App\Falta;
use App\Http\Requests\AtualizarFaltaRequest;
use App\Http\Requests\GerenciarFaltaRequest;
use App\Http\Requests\CreateAbonoRequest;
use App\Matriculado;
use App\Abono;
use App\Turma;
use App\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FaltaController extends Controller
{
    /**
     * Renderiza a view contendo as faltas de uma determinada turma
     * @param Turma $turma Instância da turma
     */
    public function show(Turma $turma)
    {
        $faltas = Falta::with('aluno')->where('turma_id', $turma->id)->get()->sortBy('data')->groupBy('data');
        $matriculados = Matriculado::with('aluno')->where('turma_id', $turma->id)->get();

        return view('falta.show')->with(['turma' => $turma, 'faltas' => $faltas, 'matriculados' => $matriculados]);
    }

    public function abono(Turma $turma, Matriculado $aluno)
    {
      $faltas = Falta::with('aluno')->where('turma_id', $turma->id)->get()->sortBy('data')->groupBy('data');

      return view('abono.show')->with(['turma' => $turma]);
    }

    public function store(CreateAbonoRequest $request)
    {//'observacao', 'arquivo', 'faltas_aluno_id', 'faltas_turma_id', , 'faltas_data','faltas_data_final', 'status'
        $form = $request->all();
        // Limpeza de CPF
        //$form['usuario'] = RequisicaoController::cleanCPF($form['usuario']);
        /*
        $destinationPath = public_path().DIRECTORY_SEPARATOR.'files';
        $fileName = '01.'.pathinfo('Hearthstone.desktop')['extension'];

        //var_dump($file->move($destinationPath.DIRECTORY_SEPARATOR.'tmp'));
        var_dump($file->move($destinationPath, $fileName));
        */

        if( !is_null($form['dataFinal'])) $dataFinal = null; //"29/06/2017"
        $dataFinal = date_create_from_format('d/m/Y', $form['dataFinal'])->format('Y-m-d H:i:s');

        $novoAbono = Abono::create([
            'observacao' => $form['observacao'],
            'aluno_id' => auth()->id(),
            'turma_id' => $form['turma'],
            'dataInicial' => $form['dataInicial'],
            'dataFinal' => $dataFinal,
            'status' => '0',
            'arquivo' => $form['arquivo']->store('abonos'),
        ]);
        dd($novoAbono);
        event(new RequestStored($novoAbono, auth()->user()));
        session()->flash('tipo', 'success');
        session()->flash('mensagem', 'Sua solicitação de abono foi enviada com sucesso. Você será notificado assim que o professor julgá-la.');
        // Envio de e-mail avisando que a requisição foi aprovada.
        $user = Ldapuser::where('cpf', $form['auth()->user()->cpf'])->first();
        if(isset($user) && isset($user->email)) Mail::to($user->email)->queue(new RequestReceived($user, $novoAbono));
        return redirect()->route('indexUserRequisicao');
    }

    /**
     * Renderiza a view de seleção de data(s) para gerenciar as faltas
     * @param Turma $turma Instância da turma
     */
    public function select(Turma $turma)
    {
        return view('falta.select')->with('turma', $turma);
    }

    /**
     * Renderiza a view para gerenciar as faltas de uma turma.
     * @param GerenciarFaltaRequest $request Requisição com campos do formulário já validados
     */
    public function manage(GerenciarFaltaRequest $request)
    {
        $form = $request->all();
        $turma = Turma::find($form['turma']);

        if(auth()->user()->can('manipular_turma', $turma))
        {
            $datas = array(); // Array com as datas que poderão ser manipuladas

            // Obtém o intervalo de dias de acorco com a opção escolhida
            if($form['opcao'] == 'dia') $datas[0] = $form['dataInicial'];
            else
            {
                $dataCorrente = Carbon::createFromFormat('d/m/Y', $form['dataInicial']);
                $dataFinal = Carbon::createFromFormat('d/m/Y', $form['dataFinal']);

                while($dataCorrente <= $dataFinal)
                {
                    $datas[] = $dataCorrente->format('d/m/Y');
                    $dataCorrente = $dataCorrente->addDay();
                }
            }

            $faltas = Falta::where('turma_id', $form['turma'])->get();
            $matriculados = Matriculado::with('aluno')->where('turma_id', $form['turma'])->get();

            return view('falta.manage')->with(['datas' => $datas, 'faltas' => $faltas, 'matriculados' => $matriculados, 'turma' => $turma]);
        }
        else return abort(403);
    }

    /**
     * Atualiza as faltas
     * @param AtualizarFaltaRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AtualizarFaltaRequest $request)
    {
        $form = $request->all();

        $matriculados = array_keys($form['falta']); // Obtém a lista de alunos

        foreach ($matriculados as $matriculado) // Para cada aluno
        {
            $datas = array_keys($form['falta'][$matriculado]); // Obtém a lista de datas

            foreach ($datas as $data) // Para cada data
            {
                try
                {
                    // Se o conteúdo do array na posição do aluno em uma data for igual a 'falta', significa que ele faltou naquele dia
                    if($form['falta'][$matriculado][$data] == "falta")
                    {
                        Falta::updateOrCreate([
                            'aluno_id' => $matriculado,
                            'turma_id' => $form['turma'],
                            'data' => Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d')
                        ]);
                    }
                    else // Senão é uma presença e se a falta existir nessa data, ela deve ser excluída
                    {
                        DB::table('faltas')->where('turma_id', $form['turma'])->where('aluno_id', $matriculado)->where('data', Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d'))->delete();
                    }
                }
                catch (\Exception $ex)
                {
                    session()->flash('tipo', 'error');
                    session()->flash('mensagem', 'Erro ao atualizar faltas: ' . $ex->getMessage());

                    return back();
                }

            }
        }

        session()->flash('tipo', 'success');
        session()->flash('mensagem', 'Faltas atualizadas com sucesso.');

        return redirect()->route('visualizarFaltas', $form['turma']);
    }



    public function download()
    {
        $data = DB::table("faltas")->limit(10)->get();
        view()->share('data',$data);
        $pdf = Falta::loadView('falta.show');
        return $pdf->download('pdf.pdfview');
    }

    public function criarAbono(CreateAbonoRequest $request)
    {
        $form = $request->all();
        $aluno = Usuario::find($form['turma']);
        $turma = Turma::find($form['turma']);
        $falta = Encarregado::find($form['encarregado']);

        if(auth()->user()->can('', $turma))
        {
            $datas = array(); // Array com as datas que poderão ser manipuladas

            // Obtém o intervalo de dias de acorco com a opção escolhida
            if($form['opcao'] == 'dia') $datas[0] = $form['dataInicial'];
            else
            {
                $dataCorrente = Carbon::createFromFormat('d/m/Y', $form['dataInicial']);
                $dataFinal = Carbon::createFromFormat('d/m/Y', $form['dataFinal']);

                while($dataCorrente <= $dataFinal)
                {
                    $datas[] = $dataCorrente->format('d/m/Y');
                    $dataCorrente = $dataCorrente->addDay();
                }
            }

            $faltas = Falta::where('turma_id', $form['turma'])->get();
            $matriculados = Matriculado::with('aluno')->where('turma_id', $form['turma'])->get();

            return view('falta.manage')->with(['datas' => $datas, 'faltas' => $faltas, 'matriculados' => $matriculados, 'turma' => $turma]);
        }
        else return abort(403);
    }

}
