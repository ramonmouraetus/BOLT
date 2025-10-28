(function () {
	tinymce.create("tinymce.plugins.Brius", {
		init: function (ed, url) {
			ed.addButton("CTAbutton", {
				title: "Adicionar Botão CTA",
				cmd: "CTAbutton",
				image: window.bolt_theme_defaut_location + "/assets/img/cta.png",
			});

			ed.addCommand("CTAbutton", function () {
				var url = prompt("Qual a URL de destino?"),
					label = prompt("Qual texto do Botão?"),
					blank = confirm('Redirecionar usuário em nova guia? Ok para SIM ou Cancelar para NÃO!'),
					shortcode,
					id = new Date().getTime();
				shortcode = '[button url="' + encodeURI(url) + '" blank="' + blank + '" id="btn-brius-' + id + '"]' + label + '[/button]';
				return !!url && !!label && ed.execCommand("mceInsertContent", 0, shortcode);
			});

			// create Youtube Video button
			ed.addButton("ytButton", {
				title: "Adicionar Link de vídeo do youtube",
				cmd: "ytButton",
				image: window.bolt_theme_defaut_location + "/assets/img/yt.png",
			});

			ed.addCommand("ytButton", function () {
				const url = prompt("Qual a URL do vídeo?");
				const full_url = new URL(url);
				const params = new URLSearchParams(full_url.search);
				const v_id = params.get('v');

				var ytShortcode = '[youtube_video v_id="' + v_id + '"][/youtube_video]';
				ed.execCommand("mceInsertContent", 0, ytShortcode);
			});
		},
	});
	tinymce.PluginManager.add("brius", tinymce.plugins.Brius);
})();

