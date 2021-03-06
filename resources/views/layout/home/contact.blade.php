<!-- Contact Section -->
<section id="contact">
   <div class="container">
      <div class="row">
         <div class="col-lg-12 text-center">
            <h2 class="section-heading">Informações para Contato</h2>
            <h3 class="section-subheading text-muted">Fale conosco! Respondemos o mais rápido possível!</h3>
         </div>
      </div>
      <div class="row">
         <div class="col-lg-12">
            <form name="sentMessage" id="contactForm" novalidate>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <input type="text" class="form-control" placeholder="Nome *" id="name" required data-validation-required-message="Por favor insira o seu nome.">
                        <p class="help-block text-danger"></p>
                     </div>
                     <div class="form-group">
                        <input type="email" class="form-control" placeholder="E-mail *" id="email" required data-validation-required-message="Por favor insira o seu e-mail.">
                        <p class="help-block text-danger"></p>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <textarea class="form-control" placeholder="Mensagem *" id="message" required data-validation-required-message="Por favor insira a mensagem."></textarea>
                        <p class="help-block text-danger"></p>
                     </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-lg-12 text-center">
                     <div id="success"></div>
                     <button type="submit" class="btn btn-xl">ENviar</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</section>
