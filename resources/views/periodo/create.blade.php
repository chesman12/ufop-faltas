@extends('layout.base')

@section('titulo')
    Criar Período
@endsection

@section('descricao')
    Complete os campos para criar um novo período.
@endsection

@section('mapa')
    <li><i class="fa fa-calendar-o"></i> Período</li>
    <li><i class="fa fa-plus"></i> Criar</li>
@endsection

@section('conteudo')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary-ufop">
                <div class="box-body">
                    <form action="{{ route('armazenarPeriodo') }}" method="post">
                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->has('ano') ? 'has-error' : '' }}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></span>
                                <input type="number" name="ano" class="form-control" value="{{ old('ano') }}" placeholder="Ano do período" title="Ano do semestre" required>
                            </div>
                            @if($errors->has('ano'))
                                <p class="help-block">{!! $errors->first('ano') !!}</p>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('periodo') ? 'has-error' : '' }}">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="number" name="periodo" class="form-control" value="{{ old('periodo') }}" placeholder="Semestre (somente o número)" title="Semestre do período (somente o número)" required>
                            </div>
                            @if($errors->has('periodo'))
                                <p class="help-block">{!! $errors->first('periodo') !!}</p>
                            @endif
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-ufop" onclick="history.back()"><i class="fa fa-arrow-left"></i> Voltar</button>
                            <button type="reset" class="btn btn-warning"><i class="fa fa-repeat"></i> Resetar</button>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Aplicar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
