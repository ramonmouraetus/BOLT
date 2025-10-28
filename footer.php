<footer>
    <div id="footer-wrapper">
        <!-- <div class="news-container news-container--first widget-area pre-footer-widget" role="complementary">
        	<?php dynamic_sidebar( 'pre-footer-widget' ); ?>
    </div> -->
    <div id="footer__page">
        <div class="footer__page__top_wrapper">
            <div class="footer__page__top">
                <div id="footer-widget">
                    <div class="widget-area footer-text" role="complementary">
                        <div class="no-mobile">
                            <?php dynamic_sidebar( 'left-footer-widget' ); ?>
                        </div>
                        <div class="no-desk">
                            <?php dynamic_sidebar( 'left-footer-widget-mob' ); ?>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="footer-menu-columns">
                <?php
                brius_menu('footer-first-menu');
                ?>
            </nav>
        </div>
        <div class="footer__page__bottom" style="display: flex">
            <div class="footer-menu-flex adreess-menu-footer footer-text">
                <?php
                dynamic_sidebar( 'subfooter-left-widget' );
                ?>
            </div>
            <div class="footer-menu-flex">
                <nav class="menu-footer">
                    <?php
                    brius_menu('footer-second-menu');
                    ?>
                </nav>
                <div class="footer__page__bottom_wrapper">
                    <div class="footer_copyright footer-text">
                        <?php echo brius_get_property( 'footer-copyright' ) . ' - ' . etus_get_translation('Todos os direitos reservados'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="footer_container">
    <div class="footer_container__copyright--is_full disclaimer-style footer-text">
        <?php dynamic_sidebar( 'disclaimer' ); ?>
    </div>
</div>
</div>
</div>
</div>
<div class="global-ui"></div>
</div>
<?php wp_footer(); ?>
</body>
</html>
