<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelector("#plusdin-newsletter-submit").addEventListener('click', () => {

      const validate = () => {
        const formNode = document.querySelector("#plusdin-newsletter-form");
        if(formNode.reportValidity()) {
          const formData = {
            name: formNode.querySelector('[name="nome-input"]').value,
            email: formNode.querySelector('[name="email-input"]').value,
            message: "",
            isTermsAccepted: formNode.querySelector('[name="termsAccepted"]').checked
          };
          subscribe(formData);
        }
      }

	  const endLoader = () => {
		document.querySelectorAll('.nlt-input').forEach(input => {
			input.disabled = false;
		});
		document.querySelector('#plusdin-newsletter-submit').disabled = false;
		document.querySelector('#plusdin-newsletter-submit').innerHTML = 'Quero receber as dicas preciosas';
	  }

      const subscribe = (data) => {
        const options = {
          method: 'POST',
          body: JSON.stringify(data),
          headers: {
            'Content-Type': 'application/json',
          },
        };

        const url = 'https://api.plusdin.com/api/emails/emails';
        // Start loader
		document.querySelectorAll('.nlt-input').forEach(input => {
			input.disabled = true;
		});
		document.querySelector('#plusdin-newsletter-submit').disabled = true;
		document.querySelector('#plusdin-newsletter-submit').innerHTML = 'Enviando...';

        fetch(url, options)
        .then(result => {
          endLoader()
          document.querySelector('#plusdin-newsletter-form').dataset.currentStep = '2';
        })
        .catch(error => {
          endLoader()
          document.querySelector('#plusdin-newsletter-form').dataset.currentStep = '3';
        })

      };
      validate();
    });

	document.querySelectorAll('.nlt-btn-btn').forEach(btn => {
		btn.addEventListener('click', () => {
			document.querySelector('#plusdin-newsletter-form').dataset.currentStep = '1';
		});
	});
  })
</script>

<style>
	@media screen and (max-width: 720px) {
		#home__page .home__newsletter .home__newsletter__wrapper .flex-container .home__newsletter__form_container {
			margin-left: 1rem;
			padding-right: 1.25rem;
			height: auto;
			margin-bottom: 32px;
		}
		#home__page .home__newsletter .home__newsletter__wrapper .flex-container .home__newsletter__form_container h3 {
			width: 100%;
		}
		.-nlt-al-s-c {
			display: flex;
			flex-flow: row;
			justify-content: center;
		}
		[data-step="2"],[data-step="3"] {
			flex-flow: column;
			align-items: center;
		}
		[data-step="2"]>h3,[data-step="3"]>h3 {
			align-self: flex-start;
		}
		.check-form-group label {
			font-size: 12px !important;
		}
		#plusdin-newsletter-form[data-current-step="2"]>[data-step="2"] {
			display: flex !important;
		}
		#plusdin-newsletter-form[data-current-step="3"]>[data-step="3"] {
			display: flex !important;
		}
		#plusdin-newsletter-submit {
			width: 100%;
		}
	}
	.home__newsletter__form_container {
		align-items: flex-start !important;
	}

	#plusdin-newsletter-form[data-current-step="1"]>[data-step] {
		display: none;
	}
	#plusdin-newsletter-form[data-current-step="1"]>[data-step="1"] {
		display: block;
	}
	#plusdin-newsletter-form[data-current-step="2"]>[data-step] {
		display: none;
	}
	#plusdin-newsletter-form[data-current-step="2"]>[data-step="2"] {
		display: block;
	}
	#plusdin-newsletter-form[data-current-step="3"]>[data-step] {
		display: none;
	}
	#plusdin-newsletter-form[data-current-step="3"]>[data-step="3"] {
		display: block;
	}


	#plusdin-newsletter-form>[data-step]>.form-group {
		position: relative;
	}
	/*
	#plusdin-newsletter-form>[data-step]>.form-group::before {
		position: absolute;
		top: 0;
		left: 0;
		content: attr(data-label);
		height: 0;
		width: 100%;
		font-size: 14px;
	}
	*/
	#plusdin-newsletter-form>[data-step]>.form-group>input {
		width: 100%;
		height: 34px;
		border-bottom: 1px solid #000000;
		margin-bottom: 20px;
	}

	.nletter-terms {
		font-weight: 400;
	}
	.nletter-terms>a {
		font-weight: bold;
		text-decoration: underline;
	}

	.nlt-btn-container {
		margin-top: 20px;
	}
	.nlt-btn-container>button {
		padding: 12px 30px !important;
	}

	.nlt-sm {
		display: flex;
		align-items: center;
		font-weight: 600;
		margin-bottom: 1.25rem;
		font-size: 30px;
		line-height: 130%;
	}
	.nlt-sm svg {
		margin-left: 0.5rem;
	}
	.nlt-fm {
		font-weight: 400;
		margin-bottom: 12px;
	}

	.nlt-btn-hover:hover {
		background-color: #00e169 !important;
		color: #000000 !important;
		cursor: pointer;
	}
</style>

<form id="plusdin-newsletter-form" data-current-step="1" data-is-loading="false">
	<div data-step="1">
		<div class="form-group" data-label="Seu nome:">
			<input
			type="text"
			name="nome-input"
			class="nlt-input"
			required
			placeholder="Seu nome:"
			/>
		</div>

		<div class="form-group" data-label="E-mail que você mais usa:">
			<input
			type="email"
			name="email-input"
			class="nlt-input"
			required
			placeholder="E-mail que você mais usa:"
			/>
		</div>

		<div class="check-form-group">
			<label for="check" class="terms nletter-terms">
			<input
				type="checkbox"
				name="termsAccepted"
				class="nlt-input"
				required
			/>
			<?php echo etus_get_translation('Li e concordo com os')?>
			<a href="https://plusdin.com.br/termos-de-uso/" target="_blank" rel="noreferrer">
			<?php echo etus_get_translation('Termos de Serviço.')?>
			</a>
			</label>
		</div>

		<div class="form-group nlt-btn-container -nlt-al-s-c">
			<button type="button" id="plusdin-newsletter-submit" class="button button--secondary product-cta nlt-btn-hover">
				<?php echo etus_get_translation(wp_is_mobile() ? 'Enviar' : 'Quero receber as dicas preciosas')?>
			</button>
		</div>
	</div>
	<div data-step="2">
		<h3 class="nlt-sm">
			<span><?php echo etus_get_translation('Feito')?></span>
			<svg width="16" height="12" viewBox="0 0 16 12" fill="#43D29E" xmlns="http://www.w3.org/2000/svg">
				<path fillRule="evenodd" clipRule="evenodd" d="M2 4L0 6L6 12L16 2L14 0L6 8L2 4Z" fill="#43D29E"></path>
			</svg>
		</h3>
		<p class="nlt-fm">
			<?php echo etus_get_translation('Cadastro realizado com sucesso! Para cadastrar outro e-mail, <b>clique abaixo.</b>')?>
		</p>
		<button type="button" class="button button--primary nlt-btn-btn nlt-btn-hover">
			<?php echo etus_get_translation('Voltar')?>
		</button>
	</div>
	<div data-step="3">
		<p class="nlt-fm">
			<?php echo etus_get_translation('Erro ao submeter o formulário! Para tentar novamente, <b>clique abaixo.</b>')?>
		</p>
		<button type="button" class="button button--primary nlt-btn-btn nlt-btn-hover">
			<?php echo etus_get_translation('Voltar')?>
		</button>
	</div>
</form>
