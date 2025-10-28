
(function () {

	const publish_form = document.querySelector('#post');
	const publish_button = document.querySelector('#publish');
	const div = document.querySelector('#wp-content-editor-tools');
	const counter = document.createElement("div");
	counter.setAttribute("id", "uri-counter");
	div.insertBefore(counter, div.firstchild);

	let interval = setInterval( () => {
		try {
			// Número de caracteres
			element = document.querySelector('#editable-post-name-full');
      console.log(element)
			uri_atual = element.innerHTML;

			// Número de palavras
			words = document.querySelector('#wp-word-count > span');
			warning = parseInt(words.innerHTML) < 600
				? "<font color='#990f0f'>O Artigo tem "+words.innerHTML+" Palavras</font>  <img width='20px'src='"+ window.bolt_theme_defaut_location +"/assets/img/warning.jpg'> &nbsp;"
				: "<font color='#0f990f'>O Artigo tem "+words.innerHTML+" Palavras</font>  <img width='20px'src='"+ window.bolt_theme_defaut_location +"/assets/img/check.png'> &nbsp;";

			// Número de parágrafos
			editor_area = document.querySelector('#content_ifr');
			paragraphs_number = editor_area.contentDocument.getElementsByTagName("p").length;
			paragraphs_list = editor_area.contentDocument.getElementsByTagName("p");
			Object.entries(paragraphs_list).forEach(([key, value]) => {
				if (value.innerText === "" || /^\n/.test(value.innerText) || /^\[button/.test(value.innerText) ||  /^\[youtube/.test(value.innerText) ||  /^https*:\/\//.test(value.innerText)) {
					paragraphs_number--;
				}
			});
			paragraphs = "<font color='#0f990f'>O Artigo tem "+paragraphs_number+" Parágrafos</font>  <img width='20px'src='"+ window.bolt_theme_defaut_location +"/assets/img/check.png'>";

			// Calculo do tempo de leitura
			words_per_minute = 200;
			read_time_minutes = words.innerHTML/words_per_minute;
			if(read_time_minutes > 0 && read_time_minutes < 1.55){
				reading_text = "<font color='#0f990f'>Tempo de leitura: 1 Minuto</font>  <img width='20px'src='"+ window.bolt_theme_defaut_location +"/assets/img/check.png'>";
			} else if(read_time_minutes >= 1.55){
				reading_text = "<font color='#0f990f'>Tempo de leitura: "+Math.round(read_time_minutes)+" Minutos</font>  <img width='20px'src='"+ window.bolt_theme_defaut_location +"/assets/img/check.png'>";
			} else {
				reading_text = '';
			}

			// Impressão dos campos: Nº de Caracteres ; Nº de Palavras ; Nº de Parágrafos ; Tempo de Leitura
			counter.innerHTML = uri_atual.length > 38
				? "<font color='#990f0f'>A URI tem "+(uri_atual.length+2)+" caracteres</font>  <img width='20px'src='"+ window.bolt_theme_defaut_location +"/assets/img/warning.jpg'> &nbsp;"+warning+paragraphs+reading_text
				: counter.innerHTML = "<font color='#0f990f'>A URI tem "+(uri_atual.length+2)+" caracteres</font> <img width='20px' src='"+ window.bolt_theme_defaut_location +"/assets/img/check.png'> &nbsp;"+warning+paragraphs+reading_text;
		} catch (error) {
			clearInterval(interval);
		}
	}, 1000);


// function teste(){
// 	uri_local = window.location.pathname;
// 	words_int = Number(words.innerHTML);
// 	(words_int < 600) ? confirm_words = 'O Artigo tem menos de 600 Palavras! \n' : confirm_words = "";
// 	(uri_atual.length > 38) ? confirm_uri = 'A URI tem mais de 40 caracteres! \n' : confirm_uri = "";

// 	if(uri_local.indexOf("post-new") != -1){
// 		if (confirm_uri != "" || confirm_words != ""){
// 			if (confirm_uri != ""){
// 				plus_confirm_uri = 'Para posts novos isso não é mais permitido! \n';
// 				option = "";
// 			}else{
// 				plus_confirm_uri = "";
// 				option = 'Clique "Cancelar" para corrigir ou "OK" para publicar mesmo assim!';
// 			}
// 			var x;
// 			var r=confirm(confirm_uri+plus_confirm_uri+confirm_words+option);
// 			if (r==true)
// 			{
// 				if (confirm_uri != ""){
// 					return false;
// 				}else{
// 					return true;
// 				}
// 			}
// 			else
// 			{
// 				return false;
// 			}
// 		}else{
// 			return true;
// 		}

// 	}else{

// 		if (confirm_uri != "" || confirm_words != ""){
// 			var x;
// 			var r=confirm(confirm_uri+confirm_words+'Clique "Cancelar" para corrigir ou "OK" para publicar mesmo assim!');
// 			if (r==true)
// 			{
// 				return true;
// 			}
// 			else
// 			{
// 				return false;
// 			}
// 		}else{
// 			return true;
// 		}
// 	}
// }
})();
