( function( api, $ ) {
  console.log('Customizer script loaded');
  api.bind('ready', () => {
    const currentLayout = api.control('home_layout').setting();

    currentLayout !== 'custom_by_categories' ?
      api.section('custom_cat_section').deactivate() :
      api.section('time_based').deactivate();

    api( 'brius_theme_settings[home_layout]', ( value ) => {
      value.bind( ( newval ) => {
        if (newval === 'time_based') {
          api.section('custom_cat_section').deactivate();
          api.section('time_based').activate()
        }else {
          api.section('custom_cat_section').activate();
          api.section('time_based').deactivate()
        }
      } );

    });

    window.currentBlocks = api.control('home_blocks_length').setting();
    const maxBlocks = 
        parseInt(
          document.querySelector('#_customize-input-home_blocks_length')
            [document.querySelector('#_customize-input-home_blocks_length')
            .length -1 ].value
        );
    const aliases = [
      'category_of_block_',
      'template_of_block_',
      'label_of_block_',
    ];

    const showBlocks = (valueSelected) => {
      for (let index = 0; index < valueSelected; index++) {

        aliases.map( alias => {
          api.control(`${alias}${index}`).activate();
        });

      }
    }

    const hideBlocks = (valueSelected) => {
      for (let index = valueSelected; index < maxBlocks; index++) {

        aliases.map( alias => {
          api.control(`${alias}${index}`).deactivate();
        });

      }
    }

    if (window.currentBlocks < maxBlocks) hideBlocks(window.currentBlocks);

    api( 'brius_theme_settings[home_blocks_length]', ( value ) => {
      value.bind( ( newval ) => {

        newval > window.currentBlocks ? showBlocks(newval) : hideBlocks(newval);
        window.currentBlocks = newval;

      });
    });
  });
  
} )( wp.customize, jQuery );
