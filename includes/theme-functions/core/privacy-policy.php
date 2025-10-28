<?php

// Adding Privacidade custom fields
function bolt_generate_privacy_page(){
    $publisher_name = get_bloginfo( 'name' );
    $publisher_subject = brius_get_custom_field( 'assunto' );
    $publisher_email = brius_get_custom_field( 'email' );
    $publisher_date = brius_get_custom_field( 'ultima-atualizacao' );
    $date = date_create($publisher_date);

	return "<p>Seja bem vindo à nossa Política de Privacidade! Aqui iremos lhe explicar quem somos, como cuidamos de suas informações e quais as medidas de proteção adotamos para garantir a maior segurança possível à nossa relação.</p>

            <h3>Primeiro, o que é o site <span>$publisher_name</span>?</h3>
            <p>Nós somos o portal informativo <span>$publisher_name</span> e temos o objetivo de compartilhar informações sobre $publisher_subject de modo simples, acessível, claro e eficiente. Também podemos promover produtos e serviços relacionados, à medida em que publicamos nosso conteúdo informativo. Outrossim, essa divulgação pode ser indireta, através de nossos banners, com a divulgação de anúncios de produtos e serviços que melhor se adequem à sua realidade, não necessariamente relacionados ao nosso assunto principal, conforme suas demais necessidades e preferências, o que explicaremos adiante.</p>

            <p>Nosso objetivo é promover conhecimento e da melhor forma para você. Para tanto, é importante que você tenha plena consciência de que nós protegemos as suas informações ao navegar pelo nosso <em>website</em>, tal como protegemos as nossas, além de sempre respeitarmos a sua privacidade.</p>

            <p>Também temos como meta esclarecer como os seus dados são tratados pelas empresas de nosso grupo. Para isso, apresentamos a nossa Política de Privacidade, que está em conformidade com as normas internacionais e nacionais de proteção de dados.</p>

            <p>Essa Política explica como os seus dados pessoais serão utilizados em nosso website, para divulgar não só os nossos serviços, produtos e publicações, mas também os de terceiros.</p>

            <p>É importante ressaltar que estas diretrizes não se aplicam aos websites ou demais serviços relacionados sob os quais não exercemos controle e, portanto, não há quaisquer responsabilidades nas relações estabelecidas entre você e os produtos e serviços de terceiros que podem lhe ser recomendados.</p>

            <p>Para começar, vamos responder a uma dúvida bem comum:</p>

            <h3>O que são dados? De quais dados estamos falando?</h3>
            <p>Responder a essa pergunta é muito importante, então vamos explicar para você algumas palavras ou termos que serão utilizados daqui em diante:</p>

            <ul>
                <li>
                    <strong>Dados Pessoais:</strong> significam quaisquer informações fornecidas e/ou coletadas pelo <span>$publisher_name</span> e/ou suas afiliadas, por qualquer meio, ainda que públicos, que o identifiquem diretamente, ou que, quando usadas em combinação com outras informações tratadas pelo <span>$publisher_name</span> sejam capazes de lhe identificar. É importante lembrar que os seus Dados Pessoais podem estar em qualquer mídia ou formato, inclusive registros eletrônicos ou computadorizados, bem como em arquivos baseados em papel.
                </li>
                <li>
                    <strong>Dados Não Pessoais:</strong> são aqueles que não permitem a sua identificação, tais como quais páginas do site foram visitadas pelo usuário, quando foram visitadas, quais hiperlinks foram clicados, quais conteúdos ou serviços foram solicitados ou indicados, entre outros dados que não sejam capazes de fornecer informações pessoais, ou seja, não permitam a sua identificação.
                </li>
                <li>
                    <strong>Usuário:</strong> qualquer pessoa física ou jurídica que acesse ou utilize, mediante qualquer meio, incluindo aparelhos móveis, celulares, tablets, computadores pessoais, navegadores de internet e demais meios de acesso que vierem a ser desenvolvidos.
                </li>
            </ul>

            <p>O usuário de um website também é conhecido como Titular de Dados Pessoais. Nesta “Política” você poderá ser referido também como usuário.</p>

            <h3>Como podemos ter acesso aos seus dados?</h3>
            <p>A quantidade e o tipo de informações coletadas pelo <span>$publisher_name</span> pode variar conforme a sua vontade, autorização, bem como o uso que você faz de nossos websites e de nossas ferramentas.</p>

            <p>Vamos lhe explicar como podemos ter acesso aos seus dados:</p>

            <ul>
                <li>
                    <strong>Dados fornecidos por você:</strong> são os dados fornecidos quando você realiza o cadastro e/ou preenche formulários oferecidos pelo <span>$publisher_name</span>, para ter acesso a conteúdos gratuitos nos nossos websites e plataformas (e-books, quizzes e semelhantes) ou para participar do processo de seleção de pessoal. A exemplo, são dados como e-mail, nome completo, número de telefone, endereço e data de nascimento.
                </li>
                <li>
                    <strong>Dados de terceiros:</strong> também podemos coletar dados sobre você de fontes disponíveis ao público, prestadores de serviços e parceiros que nos forneçam os seus dados de acordo com a legislação aplicável e respeitando os seus direitos de privacidade. Outros websites, tais como Facebook, Google e Instagram, podem nos fornecer alguns dados, desde que você os tenha previamente autorizado, para que o <span>$publisher_name</span> consiga aprimorar a sua experiência, fornecendo-lhe os melhores produtos e serviços.
                </li>
                <li>
                    <strong>Dados de navegação:</strong> são os dados que obtemos mesmo quando você não preenche nenhum formulário ou não solicita nenhum conteúdo gratuito do <span>$publisher_name</span>. Os dados de navegação, por si sós, não podem ser considerados dados pessoais, portanto, são dados não pessoais que indicam, por exemplo, quais páginas do site foram visitadas, quando foram visitadas, em quais hiperlinks você clicou, quais conteúdos ou serviços foram solicitados ou indicados por você, a sua localização aproximada (latitude e longitude); o seu endereço de IP; as informações do seu dispositivo de acesso (como identificador da unidade, identificador de publicidade, nome e tipo de sistema operacional); e as informação da sua conexão de internet dentre outros.
                </li>
            </ul>

            <h3>Por que usamos os seus dados?</h3>
            <p>O <span>$publisher_name</span> é um site que busca levar informações e conhecimento aos usuários sobre $publisher_subject.</p>

            <p>Para alcançar esse nosso objetivo, desenvolvemos as melhores pesquisas para lhe disponibilizar conteúdo valioso, capaz de esclarecer todas as suas dúvidas em relação ao $publisher_subject.</p>

            <h3>Você deve estar se perguntando, mas se a disponibilização de conteúdo é gratuita, como ocorre a monetização do <span>$publisher_name</span>?</h3>

            <ul>
                <li>
                    A geração de receita tanto para a manutenção do website, quanto para custeio de nossos investimentos em tecnologia, consiste, primordialmente, na exibição de anúncios publicitários para você, anúncios estes principalmente relacionados a produtos ou serviços relacionados à maternidade ou a outros itens que você já tenha manifestado interesse.
                </li>
                <li>
                    A publicidade direcionada, baseada em dados é não só necessária e legítima, mas eficiente ao permitir que você tenha acesso a anúncios relevantes e adequados ao seu perfil, bem como o constante acesso ao conteúdo do website, o qual carece de receita para se manter.
                </li>
                <li>
                    É importante esclarecer que fazemos uso de serviços de outras empresas para exibir anúncios no nosso site. Esses anúncios podem conter Cookies e/ou Web Beacons para coletar dados durante o próprio processo de exibição, como, por exemplo, se você clicou no anúncio, tendo interesse naquele produto ou serviço ofertado. O <span>$publisher_name</span> não tem acesso a essas informações e também não se responsabiliza pelos dados e informações coletadas por empresas terceiras, uma vez que esse procedimento não está sob nosso controle.
                </li>
                <li>
                    O <span>$publisher_name</span> exibe anúncios servidos pela rede do Google Adsense. O Google utiliza cookies para exibir anúncios nos sites que os publicam. O Cookie DART permite que sejam exibidos anúncios de acordo com as suas preferências e os seus hábitos de navegação.
                </li>
                <li>
                    Algumas das funcionalidades da plataforma do <span>$publisher_name</span> podem ser utilizadas sem que você necessite criar um cadastro prévio, informar ou apresentar qualquer tipo de dado pessoal, porém, para o cadastro em algum curso promovido em nosso website, ou ainda a solicitação de algum produto e/ou serviço, poderá ser necessária a realização do cadastro inicial com a apresentação de alguns dados pessoais, como o seu nome e e-mail.
                </li>
            </ul>

            <h3>Como usamos os seus dados?</h3>

            <ul>
                <li>
                    Fazer a sua identificação precisa e correta, garantindo assim, a sua maior segurança e proteção, evitando eventuais fraudes;
                </li>
                <li>
                    Fornecer nossos produtos, serviços e também os terceiros, a partir da análise do seu perfil. Com isso objetivamos indicar os melhores produtos e informações de acordo com a sua real necessidade e realidade, aprimorando e personalizando a sua experiência em nosso website;
                </li>
                <li>
                    Aperfeiçoar a nossa comunicação com você, enviando-lhe e-mails para que tenha acesso ao nosso conteúdo e às nossas ofertas;
                </li>
                <li>
                    Para estudos e aprimoramento de nossas tecnologias de tratamento de dados, tais como a formação de perfis de usuário, tornando esses dados não identificáveis.
                </li>
            </ul>

            <h3>O <span>$publisher_name</span> compartilha os seus dados?</h3>
            <p>Primeiramente, é importante que você saiba que o <span>$publisher_name</span> não realiza a venda de seus dados pessoais.</p>

            <p>O <span>$publisher_name</span> poderá compartilhar os seus dados pessoais fornecidos ou coletados às demais empresas do grupo, bem como a terceiros, podendo realizar o tratamento das suas informações para o oferecimento de conteúdo, produtos e serviços relacionados às nossas atividades.</p>

            <p>Em relação às empresas terceiras, ou parceiros, em regra, apenas compartilhamos dados não pessoais, como os comportamentais. Os dados pessoais serão compartilhados com a finalidade de indicação de produtos e serviços que melhor se adequem às necessidades e/ou para fornecer produtos e/ou serviços solicitados por você, desde que haja o seu interesse. Nossos parceiros somente estão autorizados a utilizar os dados pessoais para os fins específicos para os quais foram contratados, portanto, eles não irão, e nem estão autorizados, a utilizar os seus dados pessoais para outras finalidades, além da prestação dos serviços previstos contratualmente. Assim, quaisquer empresas que tiverem acesso aos seus dados pessoais deverão tratá-los de maneira consistente e de acordo com os propósitos para os quais foram coletados (ou com os quais você consentiu previamente), bem como de acordo com o que foi determinado por esta Política de Privacidade e todas as leis de privacidade e proteção de dados aplicáveis.</p>

            <p>Para propósitos administrativos como: pesquisa, planejamento, desenvolvimento de serviços, segurança e gerenciamento de risco; e</p>

            <p>Quando necessário em decorrência de obrigação legal, determinação de autoridade competente, ou decisão judicial.</p>

            <p>O <span>$publisher_name</span> se reserva no direito de auxiliar ou cooperar com qualquer autoridade judicial ou órgão governamental e poderá compartilhar os Dados Pessoais dos Usuários, a fim de estabelecer ou exercer os seus direitos legais ou proteger suas propriedades ou quando considerar que seu auxílio ou cooperação sejam necessários, para fazer cumprir ou aplicar outros acordos e/ou contratos; ou proteger os direitos, propriedade ou segurança, bem como de nossos funcionários e/ou outros usuários.</p>

            <p>Quando estiver dando cumprimento à solicitação administrativa, o <span>$publisher_name</span> poderá fornecer informações ao solicitante das informações, desde que autorizado por você, conforme determinado nos Termos e Condições Gerais de Uso.</p>

            <h3>Quais são os seus direitos e como você pode entrar em contato com a gente?</h3>
            <p>É muito importante que você, o titular dos dados, saiba que tem direito não só de obter, mas de controlar, a qualquer momento, mediante requisição ao <span>$publisher_name</span>:</p>

            <ul>
                <li>
                    a confirmação da existência de tratamento de seus dados;
                </li>
                <li>
                    o acesso aos dados;
                </li>
                <li>
                    a correção de dados incompletos, inexatos ou desatualizados;
                </li>
                <li>
                    a anonimização, o bloqueio ou eliminação de dados desnecessários, excessivos ou tratados em desconformidade com o disposto da Lei Geral de Proteção de Dados (LGPD);
                </li>
                <li>
                    a portabilidade dos dados a outro fornecedor de serviço ou produto. Neste caso, exige-se a sua requisição expressa, de acordo com a regulamentação de autoridade nacional a ser criada, observados os segredos comerciais e industriais do <span>$publisher_name</span>;
                </li>
                <li>
                    a eliminação dos dados pessoais tratados com o consentimento o seu consentimento, exceto nas hipóteses previstas na LGPD;
                </li>
                <li>
                    a informação, se houver, das entidades públicas e privadas com as quais o <span>$publisher_name</span> realizou o uso compartilhado de dados;
                </li>
                <li>
                    a informação sobre a possibilidade de não fornecer o seu consentimento e sobre as consequências desta negativa, além daquelas já mencionadas nesta Política;
                </li>
            </ul>

            <p>Para maiores informações, vide lei nº <a href=\"http://www.planalto.gov.br/ccivil_03/_ato2015-2018/2018/lei/L13709compilado.htm\">13.709/18</a>, art. 18.</p>

            <h3>Além disso...</h3>

            <ul>
                <li>
                    Você poderá, a qualquer, momento alterar a aceitação ou não dos Cookies na configuração de seu navegador, observando que ao desativar os Cookies de navegação, algumas das funcionalidades do <span>$publisher_name</span> poderão ser prejudicadas e a sua experiência ser comprometida; e
                </li>
                <li>
                    Você poderá ainda optar por desabilitar o Cookie DART na página que contém a política de privacidade da rede de anúncios e conteúdo do Google. Caso não concorde com os Cookies do Google e queira desativá-los, basta acessar a <a href=\"http://www.google.com/ads/preferences/\">página oficial</a>, para controlar a forma como o Google utiliza os Cookies coletados em sua rede de anúncios.
                </li>
            </ul>

            <p>Caso você tenha interesse em sanar as suas dúvidas, solucionar eventuais solicitações, apresentar reclamações ou ter outras informações sobre produtos e/ou serviços disponibilizados na plataforma, poderá entrar em contato conosco através do chat online ou do envio de e-mail para: $publisher_email</p>

            <p>Além disso, você também deverá utilizar esse serviço caso possua quaisquer dúvidas relacionadas à nossa relação com seus Dados Pessoais ou entenda que suas informações foram usadas de maneira incompatível com esta Política ou com as suas escolhas enquanto titular de dados.</p>

            <h3>Como protegemos os seus dados?</h3>
            <p>O <span>$publisher_name</span> adota práticas e tecnologias que estão em constante revisão e aprimoramento, conforme os avanços técnicos e regulatórios, nacionais e estrangeiros.</p>

            <p>Todas as nossas medidas para preservar seus dados contra acesso, uso, alteração, divulgação ou destruição não autorizada incluem a proteção física e lógica dos dados, comunicações criptografadas, controle de acessos, adesão ao desenvolvimento seguro de software, políticas internas de conformidade, medidas de responsabilização e mitigação de riscos que permitem a segurança no ciclo de vida dos dados. Além disso, os seus dados são armazenados em bases de dados seguras, renomadas e internacionalmente reconhecidas, tais como o GoogleCloud.</p>

            <p>Ainda assim, o <span>$publisher_name</span> não pode garantir que os nossos serviços e dos demais componentes do grupo empresarial, bem como a segurança das bases de dados utilizadas sejam completamente invioláveis. Mas não se preocupe, além da adoção das melhores práticas de segurança da informação existentes, na eventualidade da ocorrência de incidente que comprometa a segurança dos seus dados, adotaremos medidas céleres para lhe informar e reverter tal situação.</p>

            <h3>INFORMAÇÕES IMPORTANTES! LEIA COM ATENÇÃO!</h3>
            <p>Ao acessar e/ou utilizar o site <span>$publisher_name</span>, o você está ciente, declara e concorda que:</p>

            <ul>
                <li>
                    Tem no mínimo 18 (dezoito) anos e capacidade plena e expressa para a aceitação dos termos e condições desta Política de Privacidade para todos os fins de direito. Caso não se enquadre na descrição acima e/ou não concorde, ainda que em parte, com os Termos e Condições contidos nesta Política de Privacidade, não acessará e/ou utilizará os serviços oferecidos pelo <span>$publisher_name</span>, bem como os sites e serviços por ela operados;
                </li>
                <li>
                    O <span>$publisher_name</span> segue as normas e padrões internacionais de segurança no armazenamento, proteção, privacidade e transmissão de dados, destacando que nenhum método de armazenamento, proteção, privacidade e transmissão de dados são 100% seguros e invioláveis,
                </li>
                <li>
                    O <span>$publisher_name</span> <strong>NÃO</strong> envia e-mails para seus usuários solicitando pagamentos, ou confirmação de dados pessoais;
                </li>
                <li>
                    O <span>$publisher_name</span> poderá realizar pesquisas dentro do seu histórico para obter maiores e melhores informações sobre o seu perfil e assim, indicar produtos e serviços personalizados de acordo com as suas preferências e necessidades; e
                </li>
                <li>
                    O <span>$publisher_name</span> e as empresas parceiras poderão convidá-lo a participar de pesquisas de satisfação, onde serão lhe enviadas mensagens com orientações necessárias e os dados coletados serão utilizados para administrar a pesquisa, com o objetivo de buscar melhorias no sistema e nos produtos, conteúdos e serviços oferecidos pelo <span>$publisher_name</span> e por empresas parceiras.
                </li>
            </ul>

            <p>Caso você entre em contato para relatar um problema, uma dúvida ou obter um suporte conosco, está ciente e concorda que o <span>$publisher_name</span> coleta e armazena as informações deste contato, bem como as respectivas mensagens e outros dados necessários para averiguação do problema ou dúvida e que esses dados serão utilizados para solucionar eventuais questionamentos com base nas informações coletadas, sanar dúvidas, corrigir os problemas e aprimorar o sistema, melhorando a sua experiência do <span>$publisher_name</span>.</p>

            <h3>Atualização da Política de Privacidade</h3>
            <p>O mundo está sempre mudando e evoluindo, assim, nos reservamos no direito de revisar e alterar a nossa Política de tempos em tempos. Por isso é importante que você retorne à esta página, periodicamente, para garantir que esteja familiarizado com a versão mais atual, que está ciente e concorda com todas as informações que coletamos e também com o modo pelo qual tratamos os seus dados.</p>

            <p>Caso não retorne a essa página, o <span>$publisher_name</span> assumirá que você está ciente e concorda com todo o exposto nesta Política e em suas atualizações.</p>

            <p>A data da última atualização é: ". date_format($date, 'd/m/Y') . "</p>";
}
// adding Privacy Policy page
add_action('after_switch_theme', 'brius_add_privacy_policy_page');

function brius_add_privacy_policy_page() {

    $args = array('name' => 'privacidade', 'post_type' => 'page');
    $slug_query = new WP_Query($args);


    if($slug_query->found_posts == 0){
		$content = bolt_generate_privacy_page();
		// Generates post data.
		$post = array(
			'post_title'    => 'Política de Privacidade',
			'post_name'     => 'privacidade',
			'post_content'  => $content,
			'post_status'   => 'publish',
			'post_author'   => null,
			'post_type'     => 'page',
		);
        wp_insert_post( $post );
    }
}

?>
