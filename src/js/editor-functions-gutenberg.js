const { registerBlockType } = wp.blocks;
const { SelectControl, RangeControl, Spinner, TextControl,CheckboxControl } = wp.components;
console.log('jujuju');
registerBlockType("bolt-unum/add-cta-button", {
    title: "Botão CTA",
    icon: {
        // Renderizar um componente com a imagem
        src: wp.element.createElement("img", {
            src: window.bolt_theme_defaut_location + "/assets/img/cta.png",
            alt: "Icone CTA",
            style: { width: "24px", height: "24px" }, // Ajustar o tamanho do ícone
        }),
    },
    category: "common", // Categoria do bloco
    attributes: {
        url: { type: "string", default: "" },
        label: { type: "string", default: "" },
        blank: { type: "boolean", default: false },
        id: { type: "string", default: "" }
    },
    edit: function (props) {
        const { attributes: { url, label, blank, id }, setAttributes } = props;

        if ( !id ) setAttributes({ id: 'btn-brius-' + Date.now() });

        return wp.element.createElement(
            "div",
            {},
            wp.element.createElement("h3", {}, "Botão CTA"),
            wp.element.createElement(TextControl, {
                label: "URL de Destino",
                value: url,
                placeholder: "Digite a URL",
                onChange: function (newUrl) {
                    setAttributes({ url: newUrl });
                },
            }),
            wp.element.createElement(TextControl, {
                label: "Texto do Botão",
                value: label,
                placeholder: "Digite o texto do botão",
                onChange: function (newLabel) {
                    setAttributes({ label: newLabel });
                },
            }),
            wp.element.createElement(CheckboxControl, {
                label: "Abrir em nova guia?",
                checked: blank,
                onChange: function (newBlank) {
                    setAttributes({ blank: newBlank });
                },
            })
        );
    },
    save: function (props) {
        let { attributes: { url, label, blank, id } } = props;

        return `[button url="${url}" blank="${blank}" id="${id}"]${label}[/button]`;
    },
    transforms: {
        from: [
            {
                type: 'shortcode',
                tag: 'button', // Nome do shortcode a ser transformado
                transform: (attributes) => {
                    console.log('Transforming shortcode attributes:', attributes);

                    // Crie o bloco com os atributos extraídos do shortcode
                    return createBlock('bolt-unum/add-cta-button', {
                        url: attributes.url || '',
                        label: attributes.content || '',
                        blank: attributes.blank === 'true',
                        id: attributes.id || '',
                    });
                },
            },
        ],
    },
});
registerBlockType("bolt-unum/add-youtube-video", {
    title: "Adicionar Vídeo do YouTube",
    icon: "youtube",
    category: "common", // Categoria do bloco
    attributes: {
        videoUrl: { type: "string", default: "" },
    },
    edit: function (props) {
        const { attributes: { videoUrl }, setAttributes } = props;

        return wp.element.createElement(
            "div",
            {},
            wp.element.createElement("h3", {}, "Adicionar Vídeo do YouTube"),
            wp.element.createElement(TextControl, {
                label: "URL do Vídeo",
                value: videoUrl,
                placeholder: "Digite a URL do vídeo",
                onChange: function (newUrl) {
                    setAttributes({ videoUrl: newUrl });
                },
            }),
            wp.element.createElement(
                "div",
                {},
                wp.element.createElement("p", {}, "Preview do Vídeo:"),
                videoUrl
                    ? wp.element.createElement(
                        "iframe",
                        {
                            src: `https://www.youtube.com/embed/${new URLSearchParams(new URL(videoUrl).search).get("v")}`,
                            width: "100%",
                            height: "315",
                            frameBorder: "0",
                            allow: "autoplay; encrypted-media",
                            allowFullScreen: true,
                        }
                    )
                    : wp.element.createElement("p", {}, "Insira uma URL válida de um vídeo do YouTube.")
            )
        );
    },
    save: function (props) {
        const { attributes: { videoUrl } } = props;
        const videoId = new URLSearchParams(new URL(videoUrl).search).get("v");

        return `[youtube_video v_id="${videoId}"][/youtube_video]`;
    },
});

wp.domReady(() => {
    // Detecta o conteúdo do post
    const content = wp.data.select('core/editor').getEditedPostContent();

    // Regex para detectar o shortcode
    const shortcodeRegex = /\[button\s+(.*?)\](.*?)\[\/button\]/g;

    let match;
    // Processa todos os shortcodes no conteúdo
    while ((match = shortcodeRegex.exec(content)) !== null) {
        const attributesString = match[1];
        const label = match[2];

        // Analisa os atributos do shortcode
        const attributes = {};
        attributesString.split(/\s+/).forEach((attr) => {
            const [key, value] = attr.split('=');
            attributes[key] = value.replace(/['"]/g, ''); // Limpa as aspas
        });

        // Cria o bloco com os atributos do shortcode
        const block = wp.blocks.createBlock('bolt-unum/add-cta-button', {
            url: attributes.url || '',
            label: label || '',
            blank: attributes.blank === 'true',
            id: attributes.id || '',
        });

        // Substitui o conteúdo do post com o bloco criado
        wp.data.dispatch('core/editor').insertBlocks(block);
    }
});
