<?php
/**
* Cookies Plus
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate.com <info@idnovate.com>
*  @copyright 2018 idnovate.com
*  @license   See above
*/



class CookiesPlus extends Module
{
    public function __construct()
    {
        $this->name = 'cookiesplus';
        $this->tab = 'front_office_features';
        $this->version = '1.1.6';
        $this->author = 'idnovate';
        $this->module_key = '22c3b977fe9c819543a216a2fd948f22';
        $this->author_address = '0xd89bcCAeb29b2E6342a74Bc0e9C82718Ac702160';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Cookies - GDPR Cookie law (block before consent)');
        $this->description = $this->l('Make your store GDPR compliant using this module. This module lets you block the cookies until the customer gives his consent accepting the warning.');
        $this->confirmUninstall = $this->l('Are you sure you want to delete the module and the related data?');

        /* Backward compatibility */
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');
            $this->local_path = _PS_MODULE_DIR_.$this->name.'/';
        }

        $this->warning = $this->getWarnings(false);
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('header')
            && $this->registerHook('footer')
            && (!Module::isInstalled('mobile_theme') || $this->registerHook('displayMobileHeader'))
            && (version_compare(_PS_VERSION_, '1.5', '<') || $this->registerHook('displayNav'))
            && (version_compare(_PS_VERSION_, '1.5', '<') || $this->registerHook('displayNav2'))
            && (version_compare(_PS_VERSION_, '1.5', '<') || $this->registerHook('displayMyAccountBlockfooter'))
            && (version_compare(_PS_VERSION_, '1.5', '<') || $this->registerHook('tmMegaLayoutFooter'))
            && $this->registerHook('customerAccount')
            && $this->registerHook('backOfficeHeader')
            && $this->setDefaultValues()
            && $this->installOverride14()
            && Configuration::updateValue('C_P_MODULES_VALUES', Tools::jsonEncode(array()));
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        $this->removeOverride14();

        $fields = array_merge($this->getConfigValues(), $this->getLangConfigValues());

        foreach (array_keys($fields) as $key) {
            Configuration::deleteByName($key);
        }

        return true;
    }

    public function setDefaultValues()
    {
        Configuration::updateValue('C_P_EXPIRY', '365');
        Configuration::updateValue('C_P_BOTS', 'Google|Bing|Baidu|Pinterest|DuckDuckGo|MJ12bot|Yahoo|Proximic|Sogou|Slackbot|YandexAhrefs|TwitterBot|ADmantX|OpenSiteExplorer|TweetMeme|360Spider|Grapeshot|exabot|DomainTools|MeanPath|Evaliant|Genieo|YodaoBot|MSN|Sosospider|Facebook|Squider');
        Configuration::updateValue('C_P_DEFAULT_VALUE', '1');

        $modulesThirdIds = array();
        $modulesThird = array(
            'ganalytics',
            'blockfacebook',
            'cdc_googletagmanager',
            'easymarketing',
            'facebookpswallposts',
            'gremarketing',
            'statsdata',
            'gsnippetsreviews',
            'fanfacebook',
            'gplusone',
            'pinterest',
            'facebooklike',
            'likeboxfree',
            'zopimfree',
            'ps_googleanalytics',
            'spinstagramgallery',
            'facebookproductad',
            'pm_advancedtrackingwizard',
            'salesmanago',
            'facebookpsconnect'
        );
        $modules = Module::getModulesOnDisk(true);
        foreach ($modules as $module) {
            if (in_array($module->name, $modulesThird)) {
                $modulesThirdIds[] = $module->id;
            }
        }

        Configuration::updateValue('C_P_MODULES_VALUES', Tools::jsonEncode($modulesThirdIds));

        $fields = array();

        //Initialize multilang configuration values
        $translations = array();

        // English
        $translations['C_P_TEXT_BASIC']['en'] = '<p>This store asks you to accept cookies for performance, social media and advertising purposes. Social media and advertising cookies of third parties are used to offer you social media functionalities and personalized ads. Do you accept these cookies and the processing of personal data involved?</p>';
        $translations['C_P_TEXT_REQUIRED']['en'] = '<p><strong>Strictly necessary cookies</strong><br />These are Cookies that are necessary for the functioning of the Online Services. For example, they are used to enable the operation of the Online Services, enable access to secure areas of the Online Services, remember items placed in a shopping basket or cart during a session, secure the Online Services and for the administration of the Online Services (e.g., load balancing, fraud prevention). Without these Cookies, the Online Services would not function properly and this store may be unable to provide certain services.</p>';
        $translations['C_P_TEXT_3RDPARTY']['en'] = '<p><strong>Third party cookies</strong><br />This store may use third parties who use their own Cookies to store and/or access data relating to your use of, and interaction with, the Online Services. The Online Services may contain content from third parties (such as Google Maps, YouTube, ShareThis, etc.) and plugins from social media sites (like Facebook, Twitter, Linkedin, etc.). When you connect to these services, the third parties may store and/or access data using Cookies over which this store does not have control. If you are logged in to a social media website while visiting the Online Services the social media plugins may allow the social media website to receive information that you visited the Online Services and link it to your social media account. This store does not control the Cookies used by these third party services or their policies or practices. Please review those third parties\' cookie, privacy and data sharing statements.</p>';

        //Spanish
        $translations['C_P_TEXT_BASIC']['es'] = '<p>Esta tienda te pide que aceptes cookies para fines de rendimiento, redes sociales y publicidad. Las redes sociales y las cookies publicitarias de terceros se utilizan para ofrecerte funciones de redes sociales y anuncios personalizados. ¿Aceptas estas cookies y el procesamiento de datos personales involucrados?</p>';
        $translations['C_P_TEXT_REQUIRED']['es'] = '<p><strong>Cookies obligatorias</strong><br />Estas cookies son necesarias para el funcionamiento básico del sitio web y, por lo tanto, están siempre activas. Incluyen cookies que permiten recordar tus preferencias al navegar por el sitio web. Permiten que el funcionamiento del carro de la compra y el proceso de pasar por caja sea más fluido, además de proporcionar asistencia en cuestiones de seguridad y de conformidad con las normativas.</p>';
        $translations['C_P_TEXT_3RDPARTY']['es'] = '<p><strong>Cookies de terceros</strong><br />Las cookies de redes sociales te ofrecen la posibilidad de conectarte a tus redes sociales y compartir el contenido de nuestro sitio web a través de ellas. Las cookies publicitarias (o de terceros) recopilan información para adaptar mejor la publicidad a tus intereses. En algunos casos, estas cookies incluyen el procesamiento de tus datos personales. Anular la selección de estas cookies puede provocar que veas publicidad que no te resulte relevante o que no puedas vincularte de forma efectiva con Facebook, Twitter u otras redes sociales y que no puedas compartir contenido en las redes sociales.</p>';

        //French
        $translations['C_P_TEXT_BASIC']['fr'] = '<p>Ce magasin vous demande d\'accepter les cookies afin d\'optimiser les performances, les fonctionnalités des réseaux sociaux et la pertinence de la publicité. Les cookies tiers liés aux réseaux sociaux et à la publicité sont utilisés pour vous offrir des fonctionnalités optimisées sur les réseaux sociaux, ainsi que des publicités personnalisées. Acceptez-vous ces cookies ainsi que les implications associées à l\'utilisation de vos données personnelles ?</p>';
        $translations['C_P_TEXT_REQUIRED']['fr'] = '<p><strong>Cookies nécessaires</strong><br />Ces cookies sont nécessaires pour assurer le fonctionnement optimal du site et sont donc activés en permanence. Ils comprennent des cookies permettant de se souvenir de votre passage sur le site au cours d\'une session. Ils participent au fonctionnement du panier d\'achat et au processus d\'achat et vous aident en cas de problème de sécurité et pour vous conformer aux réglementations.</p>';
        $translations['C_P_TEXT_3RDPARTY']['fr'] = '<p><strong>Cookies de tiers</strong><br />Les cookies liés aux réseaux sociaux vous permettent de vous connecter à vos réseaux sociaux et de partager des contenus depuis notre site Internet. Les cookies (de tiers) liés à la publicité récupèrent des informations pour mieux cibler les publicités en fonction de vos centres d\'intérêt. Dans certains cas, ces cookies impliquent l\'utilisation de vos données personnelles. Si vous désactivez ces cookies, vous risquez de voir apparaître des publicités moins pertinentes, de ne pas pouvoir vous connecter correctement à Facebook, Twitter ou à d\'autres réseaux sociaux et de ne pas pouvoir partager des contenus sur les réseaux sociaux.</p>';

        //Polish
        $translations['C_P_TEXT_BASIC']['pl'] = '<p>Niniejsza witryna wykorzystuje pliki cookies w celu świadczenia usług na najwyższym poziomie i w sposób dostosowany do indywidualnych potrzeb. Korzystanie z witryny bez zmiany ustawień dotyczących cookies oznacza, że będą one zamieszczane w urządzeniu końcowym. Jeśli nie akceptujesz opuść tę stronę internetową.</p>';
        $translations['C_P_TEXT_REQUIRED']['pl'] = '<p><strong>Niezbędne pliki cookie</strong><br />Są to pliki cookie, które są niezbędne do funkcjonowania Usług Online. Na przykład są używane w celu umożliwienia działania Usług Online, umożliwienia dostępu do bezpiecznych obszarów Usług Online, pamiętania przedmiotów umieszczonych w koszyku lub koszyku podczas sesji, zabezpieczenia Usług Online i administrowania Usługami online. (np. równoważenie obciążenia, zapobieganie oszustwom). Bez tych plików cookie usługi online nie działałyby prawidłowo i ten sklep może nie być w stanie świadczyć określonych usług.</p>';
        $translations['C_P_TEXT_3RDPARTY']['pl'] = '<p><strong>Pliki cookie stron trzecich</strong><br />Ten sklep może wykorzystywać strony trzecie, które używają własnych plików cookie do przechowywania i / lub uzyskiwania dostępu do danych związanych z korzystaniem z usług online i współdziałaniem z nimi. Usługi online mogą zawierać treści od stron trzecich (takich jak Mapy Google, YouTube, ShareThis itp.) Oraz wtyczki z serwisów społecznościowych (takich jak Facebook, Twitter, Linkedin itp.). Po połączeniu się z tymi usługami strony trzecie mogą przechowywać i / lub uzyskiwać dostęp do danych za pomocą plików cookie, nad którymi ten sklep nie ma kontroli. Jeśli jesteś zalogowany na portalu społecznościowym podczas odwiedzania usług online, wtyczki mediów społecznościowych mogą zezwolić stronie internetowej mediów społecznościowych na otrzymywanie informacji o odwiedzonych Usługach internetowych i powiązanie ich z kontem społecznościowym. Ten sklep nie kontroluje plików cookie używanych przez te usługi stron trzecich ani ich polityki lub praktyk. Zapoznaj się z instrukcjami dotyczącymi plików cookie, prywatności i udostępniania danych stron trzecich.</p>';

        //Romanian
        $translations['C_P_TEXT_BASIC']['ro'] = '<p>Acest magazin vă solicită să acceptați cookie-uri pentru performanță, media și publicitate. Mediile sociale și cookie-urile de publicitate ale unor terțe părți sunt utilizate pentru a vă oferi funcții de social media și anunțuri personalizate. Acceptați aceste cookie-uri și procesarea datelor personale implicate?</p>';
        $translations['C_P_TEXT_REQUIRED']['ro'] = '<p><strong>Cookie-urile strict necesare</strong><br />Acestea sunt modulele cookie necesare pentru funcționarea serviciilor online. De exemplu, ele sunt utilizate pentru a permite funcționarea serviciilor online, pentru a permite accesul la zonele securizate ale serviciilor online, pentru a reaminti articolele plasate într-un coș de cumpărături în timpul unei sesiuni, pentru a asigura serviciile online și pentru a administra serviciile online (de exemplu, echilibrarea încărcăturii, prevenirea fraudei). Fără aceste cookie-uri, serviciile online nu ar funcționa corect și acest magazin ar putea să nu poată furniza anumite servicii.</p>';
        $translations['C_P_TEXT_3RDPARTY']['ro'] = '<p><strong>Cookie-urile terțe</strong><br />Acest magazin poate utiliza terțe părți care utilizează propriile module cookie pentru a stoca și / sau accesa date referitoare la utilizarea și interacțiunea cu serviciile online. Serviciile online pot conține conținut de la terțe părți (cum ar fi Google, YouTube, Facebook etc.) și pluginuri de pe site-uri de socializare (cum ar fi Facebook, Twitter, Linkedin etc.). Când vă conectați la aceste servicii, terțele părți pot să stocheze și / sau să acceseze date utilizând cookie-uri pe care acest magazin nu are control. Dacă sunteți conectat (ă) la un site web de socializare în timp ce vizitați serviciile online, pluginurile sociale pot permite site-ului media social să primească informații pe care le-ați vizitat Serviciile online și să le conectați la contul dvs. social media. Acest magazin nu controlează modulele cookie utilizate de aceste servicii terțe părți sau politicile sau practicile acestora. Consultați declarațiile cookie-urilor, confidențialității și partajării acestor terțe părți.</p>';

        //Portuguese
        $translations['C_P_TEXT_BASIC']['pt'] = '<p>Esta loja pede-te para aceitares cookies para efeitos de desempenho, redes sociais e publicidade. Os cookies de publicidade e de redes sociais de terceiros são utilizados para te oferecer funcionalidades sociais e anúncios personalizados. Aceitas estes cookies e o processamento de dados pessoais envolvidos?</p>';
        $translations['C_P_TEXT_REQUIRED']['pt'] = '<p><strong>Cookies necessários</strong><br />Estes cookies são necessários para a funcionalidade básica do site e por isso estão sempre ativados. Estes incluem cookies que te permitem seres recordado à medida que exploras o site durante uma única sessão. Ajudam a tornar possível o processo de carrinho de compras e de pagamento, bem como a auxiliar em questões de segurança e no cumprimento dos regulamentos.</p>';
        $translations['C_P_TEXT_3RDPARTY']['pt'] = '<p><strong>Cookies de terceiros</strong><br />Os cookies de meios de comunicação sociais oferecem a possibilidade de te ligares às tuas redes sociais e de partilhar conteúdo multimédia do nosso website através das redes sociais. Os cookies de publicidade (de terceiros) recolhem informações para ajudar a adequar melhor a publicidade aos teus interesses, tanto dentro como fora de sites da esta loja. Em alguns casos, estes cookies envolvem o processamento dos teus dados pessoais. Anular a seleção destes cookies pode resultar em veres publicidade que não é importante para ti ou em não conseguires uma ligação funcional ao Facebook, Twitter ou outra rede social e/ou em não poderes partilhar conteúdo em meios de comunicação sociais.</p>';

        //Slovak
        $translations['C_P_TEXT_BASIC']['sk'] = '<p>Náš obchod používa súbory cookie za účelom zabezpečenia nevyhnutnej funkcionality stránok, sociálnych médií a marketingu. Súhlasíte s týmito súbormi cookies a spracovaním príslušných osobných údajov?</p>';
        $translations['C_P_TEXT_REQUIRED']['sk'] = '<p><strong>Nevyhnutné cookies</strong><br />Tieto sú cookies, ktoré sú potrebné pre správne fungovanie obchodu, napríklad umožňujú prístup do častí pre registrovaných zákazníkov, zapamätanie si obsahu nákupného košíka, zabezpečenie a administráciu online služieb. Bez týchto cookies stránky nebudú správne fungovať a obchod nebude môcť poskytnúť určité služby.<p>';
        $translations['C_P_TEXT_3RDPARTY']['sk'] = '<p><strong>Cookies tretích strán</strong><br />Tento obchod môže používať služby tretích strán, ktoré používajú ich vlastné cookies, aby ukladali alebo pristupovali k údajom o vašom používaní tohto obchodu. Napríklad obsah tretích strán (YouTube, Google Maps, atď.) a moduly zo sociálnych sietí(Facebook, Google+, atď). Keď použijete tieto služby, tretie strany môžu uložiť alebo pristúpiť k údajom uloženým v cookies, nad ktorými tento obchod nemá kontrolu. Keď ste prihlásení do sociálnej siete (napr. Facebook) počas doby, kedy používate tento obchod, moduly sociálnych sietí môžu umožniť webstránke sociálnych sietí získať informáciu o tom, že ste obchod navštívili a spojiť ju s vašim účtom na sociálnej sieti. Tento obchod nemá kontrolu nad cookies používanými týmito tretími stranami ani ich službami a praktikami. Prosím prečítajte si vyhlásenie o ochrane osobných údajov a používaní cookies týchto tretích strán.</p>';

        //Nederlands
        $translations['C_P_TEXT_BASIC']['nl'] = '<p>Deze winkel vraagt je om cookies te accepteren voor betere prestaties en voor sociale-media- en advertentiedoeleinden. Er worden sociale-media- en advertentiecookies van derden gebruikt om je sociale-mediafunctionaliteit en persoonlijke advertenties te bieden. Accepteer je deze cookies en de bijbehorende verwerking van je persoonsgegevens?</p>';
        $translations['C_P_TEXT_REQUIRED']['nl'] = '<p><strong>Verplichte cookies</strong><br />Deze cookies zijn nodig voor de basisfunctionaliteit van de website en zijn daarom permanent ingeschakeld. Tot functionele cookies behoren cookies die ervoor zorgen dat je herkend wordt tijdens het verkennen van de website binnen een en dezelfde sessie. Ze maken de winkelwagen en afrekenen mogelijk, en helpen bij beveiligingskwesties en de naleving van regelgeving.<p>';
        $translations['C_P_TEXT_3RDPARTY']['nl'] = '<p><strong>Cookies van derden</strong><br />Sociale-mediacookies bieden je de mogelijkheid om in te loggen bij je sociale netwerken en content van onze website via sociale media te delen. Advertentiecookies (van derden) verzamelen informatie zodat we advertenties beter kunnen afstemmen op jouw interesses. In sommige gevallen worden met deze cookies je persoonsgegevens verwerkt. Lees ons Privacy- en cookiesbeleid voor meer informatie over de verwerking van je persoonsgegevens: . Als je deze cookies uitschakelt, krijg je mogelijk advertenties te zien die niet relevant zijn voor jou, kun je misschien geen verbinding maken met Facebook, Twitter of andere sociale netwerken, of kun je geen content delen via sociale media.</p>';

        //Deutsch
        $translations['C_P_TEXT_BASIC']['de'] = '<p>Für eine optimal Performance, eine reibungslose Verwendung sozialer Medien und aus Werbezwecken empfiehlt dir dieser Laden, der Verwendung von Cookies zuzustimmen. Durch Cookies von sozialen Medien und Werbecookies von Drittparteien hast du Zugriff auf Social-Media-Funktionen und erhältst personalisierte Werbung. Stimmst du der Verwendung dieser Cookies und der damit verbundenen Verarbeitung deiner persönlichen Daten zu?</p>';
        $translations['C_P_TEXT_REQUIRED']['de'] = '<p><strong>Obligatorische Cookies</strong><br />Diese Cookies sind immer aktiviert, da sie für Grundfunktionen der Website erforderlich sind. Hierzu zählen Cookies, mit denen gespeichert werden kann, wo auf der Seite du dich bewegst – während eines Besuchs. Mit ihrer Hilfe funktionieren die Bereiche Warenkorb und Kasse reibungslos, außerdem tragen sie zur sicheren und vorschriftsmäßigen Nutzung der Seite bei.<p>';
        $translations['C_P_TEXT_3RDPARTY']['de'] = '<p><strong>Cookies von Drittanbietern</strong><br />Cookies von sozialen Medien ermöglichen es dir, dich mit deinen sozialen Netzwerken zu verbinden und Inhalte unserer Website über soziale Medien zu teilen. Werbecookies (von Drittparteien) erfassen Informationen, mithilfe derer Werbung besser an deine Interessen angepasst wird. In manchen Fällen ist hierfür die Verarbeitung deiner persönlichen Daten erforderlich. Weitere Informationen zur Verarbeitung deiner persönlichen Daten findest du in unserer Datenschutz- und Cookie-Richtlinie. Das Deaktivieren dieser Cookies kann zur Anzeige von Werbung führen, die für dich weniger interessant ist. Auch der problemlose Austausch mit Facebook, Twitter oder anderen sozialen Netzwerken sowie das Teilen von Inhalten auf sozialen Medien kann beeinträchtigt werden.</p>';

        //Greek
        $translations['C_P_TEXT_BASIC']['gr'] = '<p>Αυτό το κατάστημα σου ζητά να αποδεχτείς τα cookies για σκοπούς απόδοσης, κοινωνικής δικτύωσης και διαφήμισης. Τα cookies κοινωνικής δικτύωσης και διαφήμισης παρέχονται από τρίτα μέρη για να σου προσφέρουν λειτουργίες κοινωνικής δικτύωσης και εξατομικευμένες διαφημίσεις. Αποδέχεσαι αυτά τα cookies και την συνεπαγόμενη επεξεργασία προσωπικών δεδομένων;</p>';
        $translations['C_P_TEXT_REQUIRED']['gr'] = '<p><strong>Υποχρεωτικά cookies</strong><br />Αυτά τα cookies είναι απαραίτητα για τη στοιχειώδη λειτουργία του ιστότοπου και επομένως είναι πάντα ενεργοποιημένα. Σε αυτά περιλαμβάνονται και τα cookies που αποθηκεύουν τα στοιχεία σου όσο βρίσκεσαι στις σελίδες μας. Επιτρέπουν την προσθήκη προϊόντων στο καλάθι αλλά και τη διαδικασία checkout, ενώ βοηθούν στην επίλυση προβλημάτων ασφαλείας και στη συμμόρφωση με τους κανονισμούς.<p>';
        $translations['C_P_TEXT_3RDPARTY']['gr'] = '<p><strong>τρίτων μερών cookies</strong><br />Με τα cookies κοινωνικής δικτύωσης μπορείς να συνδεθείς στα κοινωνικά σου δίκτυα και να μοιραστείς εκεί περιεχόμενο από τον ιστότοπό μας. Τα διαφημιστικά cookies (τρίτων μερών) συλλέγουν πληροφορίες που συμβάλλουν στην καλύτερη εξατομίκευση των διαφημίσεων ώστε να ανταποκρίνονται στα ενδιαφέροντά σου. Σε ορισμένες περιπτώσεις, αυτά τα cookies συνεπάγονται την επεξεργασία των προσωπικών σου δεδομένων. Για περισσότερες πληροφορίες για την επεξεργασία προσωπικών δεδομένων, δες την Πολιτική Απορρήτου και Cookies . Αν απενεργοποιήσεις αυτά τα cookies, μπορεί να βλέπεις διαφημίσεις που δεν έχουν σχέση με τα ενδιαφέροντά σου ή να μην μπορείς να συνδεθείς κανονικά με το Facebook, το Twitter ή άλλα κοινωνικά δίκτυα και να μοιραστείς περιεχόμενο.</p>';

        //Italian
        $translations['C_P_TEXT_BASIC']['it'] = '<p>Questo negozio richiede di accettare i cookie per scopi legati a prestazioni, social media e annunci pubblicitari. I cookie di terze parti per social media e a scopo pubblicitario vengono utilizzati per offrire funzionalità social e annunci pubblicitari personalizzati. Accetti i cookie e l\'elaborazione dei dati personali interessati?</p>';
        $translations['C_P_TEXT_REQUIRED']['it'] = '<p><strong>Cookie obbligatori</strong><br />Questi cookie sono richiesti per le funzionalità di base del sito e sono, pertanto, sempre abilitati. Si tratta di cookie che consentono di riconoscere l\'utente che utilizza il sito durante un\'unica sessione. Questo tipo di cookie consente di riempire il carrello, eseguire facilmente le operazioni di pagamento, risolvere problemi legati alla sicurezza e garantire la conformità alle normative vigenti.<p>';
        $translations['C_P_TEXT_3RDPARTY']['it'] = '<p><strong>Cookie di terze parti</strong><br />I cookie per social media offrono la possibilità di connetterti ai tuoi social e condividere contenuti dal nostro sito Web mediante social network. I cookie per pubblicità (di terze parti) raccolgono dati utili che ci consentono di fornirti informazioni pubblicitarie che rispondono ai tuoi interessi. In alcuni casi, questi cookie prevedono l\'elaborazione dei tuoi dati personali. Per ulteriori informazioni sull\'elaborazione dei dati personali, consulta la Politica sulla privacy e sui cookie. La disabilitazione di questi cookie può comportare la visualizzazione di annunci pubblicitari non pertinenti oppure impedire il collegamento a Facebook, Twitter o altri social network e/o la condivisione di contenuti sui social media.</p>';

        //Svenska
        $translations['C_P_TEXT_BASIC']['sv'] = '<p>Denna butik ber dig att godkänna cookies för anpassning av prestanda, sociala medier och marknadsföring. Tredjepartscookies för sociala medier och marknadsföring används för att erbjuda anpassade annonser och funktioner för sociala medier. Godkänner du dessa cookies och behandlingen av berörda personuppgifter?</p>';
        $translations['C_P_TEXT_REQUIRED']['sv'] = '<p><strong>Obligatoriska cookies</strong><br />Cookies krävs för grundläggande webbplatsfunktioner och är därför alltid aktiverade. Bland annat finns cookies som kommer ihåg var på webbplatsen du varit under en session. Om du begär det kan de komma ihåg det mellan sessioner. De gör det möjligt att använda varukorgen och gå till kassan. De hjälper dig också vid säkerhetsproblem och ser till att reglerna följs.<p>';
        $translations['C_P_TEXT_3RDPARTY']['sv'] = '<p><strong>Tredje part cookies</strong><br />Med cookies från sociala medier kan du ansluta till dina sociala nätverk och dela innehåll från vår webbplats på sociala medier. Reklamcookies (från tredje part) samlar information för att presentera annonser som är mer relevanta för dig. I vissa fall innebär cookies att dina personuppgifter behandlas. Mer information om sådan bearbetning av personuppgifter finns i vår Sekretess- och cookiepolicy. Om du väljer bort dessa cookies kanske du ser reklam som inte är relevant för dig. Du kanske inte heller kan ansluta till Facebook, Twitter eller andra sociala nätverk och/eller dela innehåll på sociala medier.</p>';

        //Dansk
        $translations['C_P_TEXT_BASIC']['da'] = '<p>Denne butik beder dig om at acceptere cookies til performance, sociale medier og reklameformål. Sociale medier og tredjeparts annoncecookies bruges til at tilbyde dig funktionaliteter og tilpassede annoncer på sociale medier. Vil du acceptere disse cookies og behandlingen af implicerede personoplysninger?</p>';
        $translations['C_P_TEXT_REQUIRED']['da'] = '<p><strong>Obligatoriske cookies</strong><br />Disse cookies er nødvendige for grundlæggende webstedsfunktionalitet og derfor altid aktiveret. Disse omfatter cookies, der tillader at din udforskning af webstedet bliver husket inden for en enkelt session. De understøtter indkøbsvognen og betalingsprocessen og hjælper med sikkerhedsspørgsmål og med at opfylde reglerne.<p>';
        $translations['C_P_TEXT_3RDPARTY']['da'] = '<p><strong>Tredjeparts cookies</strong><br />Cookies på sociale medier gør det muligt at forbinde dig med dine sociale netværk og dele indhold fra vores hjemmeside via sociale medier. Reklame-cookies (tredjeparts) indsamler oplysninger for at hjælpe med at skræddersy reklamer i forhold til dine interesser. I nogle tilfælde omfatter disse cookies behandling af dine personlige data. For mere information om denne behandling af personoplysninger, læs vores Politik for beskyttelse af personlige oplysninger og cookies. Fravælgelse af disse cookies kan resultere i, at du får reklamer, der ikke er så relevante for dig, eller at du ikke kan forbinde effektivt med Facebook, Twitter eller andre sociale netværk, og/eller at det ikke er muligt at dele indhold på sociale medier.</p>';

        //Norsk
        $translations['C_P_TEXT_BASIC']['no'] = '<p>Denne butikken spør om du godtar informasjonskapsler for ytelsesformål, sosiale medier og annonsering. Informasjonskapsler for sosiale medier og annonsering fra tredjeparter brukes for å tilby deg funksjoner på sosiale medier og tilpassede annonser. Godtar du disse informasjonskapslene og den involverte behandlingen av personopplysningene dine?</p>';
        $translations['C_P_TEXT_REQUIRED']['no'] = '<p><strong>Obligatorisk cookies</strong><br />Disse informasjonskapslene er nødvendig for nettsidens grunnleggende funksjoner og er derfor alltid aktivert. Disse inkluderer informasjonskapsler som gjør at du blir husket når du utforsker nettsiden innenfor én enkelt økt eller, hvis du ber om det, fra økt til økt. De bidrar til å muliggjøre handlevogn- og betalingsprosessen samt bistår i sikkerhetsspørsmål og at forskriftene følges.<p>';
        $translations['C_P_TEXT_3RDPARTY']['no'] = '<p><strong>Tredjeparts cookies</strong><br />Informasjonskapsler på sosiale medier gir muligheten til å koble deg til det sosiale nettverket ditt og dele innhold fra nettstedet vårt via sosiale medier. Informasjonskapsler på annonser (fra tredjeparter) samler informasjon som bidrar til bedre å kunne tilpasse annonseringen i forhold til dine interesser. I noen tilfeller kan disse informasjonskapslene innebære behandling av dine personopplysninger. Hvis du vil ha mer informasjon om denne behandlingen av personopplysninger, kan du se våre Vilkår for personvern og informasjonskapsler. Hvis du deaktiverer disse informasjonskapslene, kan det resultere i at du ser annonser som ikke er like relevante for deg, eller at du ikke er i stand til å koble til like effektivt med Facebook, Twitter eller andre sosiale nettverk, og/eller at du ikke kan dele innhold på sosiale medier.</p>';

        //ČEŠTINA
        $translations['C_P_TEXT_BASIC']['cs'] = '<p>Společnost tento obchod žádá o tvůj souhlas s používáním souborů cookie pro účely výkonu, sociálních médií a reklamy. Sociální média a reklamní soubory cookie třetích stran používáme k tomu, abychom ti mohli nabízet funkce sociálních médií a přizpůsobenou reklamu. Další informace nebo doplnění nastavení získáš kliknutím na tlačítko „Více informací“ nebo otevřením nabídky „Nastavení souborů cookie“ v dolní části webové stránky. Podrobnější informace o souborech cookie a zpracování tvých osobních údajů najdeš v našich Zásadách ochrany osobních údajů a používání souborů cookie. Souhlasíš s používáním souborů cookie a zpracováním souvisejících osobních údajů?</p>';
        $translations['C_P_TEXT_REQUIRED']['cs'] = '<p><strong>Povinné soubory cookie</strong><br />Tyto soubory cookie jsou nutné pro základní funkce stránky, a jsou proto vždy povolené. Mezi ně patří soubory cookie, které stránce umožňují si tě zapamatovat při procházení stránky v rámci jedné relace nebo. Umožňují používat nákupní košík a pokladnu a také pomáhají se zabezpečením a plněním předpisů.<p>';
        $translations['C_P_TEXT_3RDPARTY']['cs'] = '<p><strong>Soubory cookie třetích stran</strong><br />Díky souborům cookie sociálních médií se můžeš připojit ke svým sociálním sítím a prostřednictvím sociálních médií sdílet obsah z naší webové stránky. Reklamní soubory cookie (třetích stran) shromažďují informace pro lepší přizpůsobení reklamy tvým zájmům. V některých případech tyto soubory cookie zpracovávají tvé osobní údaje. Pokud chceš získat více informací o zpracování osobních údajů, přečti si naše Zásady ochrany osobních údajů a používání souborů cookie. Pokud zakážeš soubory cookie, mohou se zobrazovat reklamy, které méně souvisejí s tvými zájmy, nebo nebudeš moci účinně používat odkazy na Facebook, Twitter či jiné sociální sítě anebo nebudeš moci sdílet obsah na sociálních médiích.</p>';

        //Magyar
        $translations['C_P_TEXT_BASIC']['hu'] = '<p>Ez a bolt a megfelelő teljesítmény és a közösségimédia-funkciók biztosításához, valamint a hirdetések megjelenítéséhez kéri a cookie-k elfogadását. A harmadik felek közösségimédia- és hirdetési cookie-jai használatával biztosítunk közösségimédia-funkciókat, és jelenítünk meg személyre szabott reklámokat. Ha több információra van szükséged, vagy kiegészítenéd a beállításaidat, kattints a További információ gombra, vagy keresd fel a webhely alsó részéről elérhető Cookie-beállítások területet. A cookie-kkal kapcsolatos további információért, valamint a személyes adatok feldolgozásának ismertetéséért tekintsd meg Adatvédelmi és cookie-kra vonatkozó szabályzatunkat. Elfogadod ezeket a cookie-kat és az érintett személyes adatok feldolgozását?</p>';
        $translations['C_P_TEXT_REQUIRED']['hu'] = '<p><strong>Kötelező cookie-k</strong><br />Ezekre a cookie-kra a webhely alapfunkcióinak biztosításához van szükség, ezért mindig engedélyezve vannak. Szerepelnek közöttük olyan cookie-k, amelyek lehetővé teszik, hogy a rendszer megjegyezzen téged, amikor egy munkameneten belül a webhelyet böngészed. Segítenek a bevásárlókosár működtetésében és a fizetési folyamat lebonyolításában, valamint a biztonsági funkciók működését és a szabályok betartását is lehetővé teszik.<p>';
        $translations['C_P_TEXT_3RDPARTY']['hu'] = '<p><strong>Harmadik fél cookie-jai</strong><br />A közösségi média cookie-k lehetővé teszik, hogy csatlakozz közösségi portáljaidhoz és rajtuk keresztül megoszthasd a weboldalunkon lévő tartalmakat. A (harmadik féltől származó) reklám cookie-k adatgyűjtése azt a célt szolgálja, hogy az érdeklődésednek megfelelő reklámok jelenjenek meg. Bizonyos esetekben ezek a cookie-k feldolgozzák a személyes adataidat. A személyes adatok ily módon történő feldolgozásával kapcsolatos információkért lásd Adatvédelmi és cookie-kra vonatkozó szabályzatunkat. Ha nem engedélyezed ezeket a cookie-kat, akkor előfordulhat, hogy számodra nem annyira fontos reklámok jelennek meg, vagy nem tudsz hatékonyan kapcsolódni a Facebookhoz, Twitterhez, illetve egyéb közösségi portálokhoz és/vagy nem tudsz tartalmakat megosztani a közösségi oldalakon.</p>';

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                $languageCode = $lang['iso_code'];
            } else {
                $languageCode = strtok($lang['language_code'], '-');
            }

            $fields['C_P_TEXT_BASIC'][$lang['id_lang']] = isset($translations['C_P_TEXT_BASIC'][$languageCode]) ? $translations['C_P_TEXT_BASIC'][$languageCode] : $translations['C_P_TEXT_BASIC']['en'];
            $fields['C_P_TEXT_REQUIRED'][$lang['id_lang']] = isset($translations['C_P_TEXT_REQUIRED'][$languageCode]) ? $translations['C_P_TEXT_REQUIRED'][$languageCode] : $translations['C_P_TEXT_REQUIRED']['en'];
            $fields['C_P_TEXT_3RDPARTY'][$lang['id_lang']] = isset($translations['C_P_TEXT_3RDPARTY'][$languageCode]) ? $translations['C_P_TEXT_3RDPARTY'][$languageCode] : $translations['C_P_TEXT_3RDPARTY']['en'];
            //$fields['C_P_TEXT_REJECT'][$lang['id_lang']] = isset($translations['C_P_TEXT_REJECT'][$languageCode]) ? $translations['C_P_TEXT_REJECT'][$languageCode] : $translations['C_P_TEXT_REJECT']['en'];

            //$fields['C_P_REJECT_URL'][$lang['id_lang']] = 'https://www.google.com/';
        }

        Configuration::updateValue('C_P_TEXT_BASIC', $fields['C_P_TEXT_BASIC'], true);
        Configuration::updateValue('C_P_TEXT_REQUIRED', $fields['C_P_TEXT_REQUIRED'], true);
        Configuration::updateValue('C_P_TEXT_3RDPARTY', $fields['C_P_TEXT_3RDPARTY'], true);
        //Configuration::updateValue('C_P_TEXT_REJECT', $fields['C_P_TEXT_REJECT'], true);
        //Configuration::updateValue('C_P_REJECT_URL', $fields['C_P_REJECT_URL'], true);

        return true;
    }

    public function installOverride14()
    {
        if (_PS_VERSION_ > '1.5') {
            return true;
        }

        $errors = array();

        // Make sure the environment is OK
        if (!is_dir(dirname(__FILE__).'/../../override/classes/')) {
            mkdir(dirname(__FILE__).'/../../override/classes/', 0777, true);
        }

        if (file_exists(dirname(__FILE__).'/../../override/classes/Cookie.php')) {
            if (!md5_file(dirname(__FILE__).'/../../override/classes/Cookie.php') == md5_file(dirname(__FILE__).'/override/classes/Cookie.php')) {
                $errors[] = '/override/classes/Cookie.php';
            }
        }

        if (!copy(dirname(__FILE__).'/override/classes/Cookie.php', dirname(__FILE__).'/../../override/classes/Cookie.php')) {
            $errors[] = '/override/classes/Cookie.php';
        }

        if (file_exists(dirname(__FILE__).'/../../override/classes/Hook.php')) {
            if (!md5_file(dirname(__FILE__).'/../../override/classes/Hook.php') == md5_file(dirname(__FILE__).'/override/classes/Hook.php')) {
                $errors[] = '/override/classes/Hook.php';
            }
        }

        if (!copy(dirname(__FILE__).'/override/classes/Hook.php', dirname(__FILE__).'/../../override/classes/Hook.php')) {
            $errors[] = '/override/classes/Hook.php';
        }

        if (file_exists(dirname(__FILE__).'/../../override/classes/Module.php')) {
            if (!md5_file(dirname(__FILE__).'/../../override/classes/Module.php') == md5_file(dirname(__FILE__).'/override_14/Module.php')) {
                $errors[] = '/override/classes/Module.php';
            }
        }

        if (!copy(dirname(__FILE__).'/override_14/Module.php', dirname(__FILE__).'/../../override/classes/Module.php')) {
            $errors[] = '/override/classes/Module.php';
        }

        if (count($errors)) {
            die('<div class="conf warn">
                                <img src="../img/admin/warn2.png" alt="" title="" />'.
                $this->l('The module was successfully installed (').
                '<a href="?tab=AdminModules&configure=cookiesplus&token='.Tools::getAdminTokenLite('AdminModules').'&tab_module=front_office_features&module_name=cookiesplus" style="color: blue;">'.$this->l('configure').'</a>'.
                $this->l(') but the following file already exist. Please, merge the file manually.').'<br />'.
                implode('<br />', $errors).
                '</div>');
        }

        return true;
    }

    public function removeOverride14()
    {
        if (_PS_VERSION_ > '1.5') {
            return true;
        }

        // Make sure the environment is OK
        if (!is_dir(dirname(__FILE__).'/../../override/classes/')) {
            mkdir(dirname(__FILE__).'/../../override/classes/', 0777, true);
        }

        if (file_exists(dirname(__FILE__).'/../../override/classes/Cookie.php')) {
            if (!md5_file(dirname(__FILE__).'/../../override/classes/Cookie.php') == md5_file(dirname(__FILE__).'/override/classes/Cookie.php')) {
                return false;
            }
            if (!unlink(dirname(__FILE__).'/../../override/classes/Cookie.php')) {
                return false;
            }
        }

        if (file_exists(dirname(__FILE__).'/../../override/classes/Hook.php')) {
            if (!md5_file(dirname(__FILE__).'/../../override/classes/Hook.php') == md5_file(dirname(__FILE__).'/override/classes/Hook.php')) {
                return false;
            }
            if (!unlink(dirname(__FILE__).'/../../override/classes/Hook.php')) {
                return false;
            }
        }

        if (file_exists(dirname(__FILE__).'/../../override/classes/Module.php')) {
            if (!md5_file(dirname(__FILE__).'/../../override/classes/Module.php') == md5_file(dirname(__FILE__).'/override_14/Module.php')) {
                return false;
            }
            if (!unlink(dirname(__FILE__).'/../../override/classes/Module.php')) {
                return false;
            }
        }

        return true;
    }

    public function getContent()
    {
        $this->context->controller->addCSS($this->_path.'views/css/cookiesplus.admin.css');

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->context->controller->addJS($this->_path.'views/js/cookiesplus.admin.js');
            $this->context->controller->addJS($this->_path.'views/js/tabs.js');
        }

        $html = '';
        if (((bool)Tools::isSubmit('submitCookiesPlusModule')) == true) {
            $html .= $this->postProcess();
        }

        if ($warnings = $this->getWarnings()) {
            $html .= $this->displayError($warnings);
            return $html;
        }

        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            return $html . $this->renderForm14();
        } else {
            return $html . $this->renderForm();
        }
    }

    protected function renderForm()
    {
        $html = '';

        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCookiesPlusModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $html .= $helper->generateForm($this->getConfigForm());

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->context->smarty->assign(array(
                'this_path'     => $this->_path,
                'support_id'    => '21644'
            ));

            $available_lang_codes = array('en', 'es', 'fr', 'it', 'de');
            $default_lang_code = 'en';
            $template_iso_suffix = in_array(strtok($this->context->language->language_code, '-'), $available_lang_codes) ? strtok($this->context->language->language_code, '-') : $default_lang_code;
            $html .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/company/information_'.$template_iso_suffix.'.tpl');
        }

        return $html;
    }

    protected function renderForm14()
    {
        $helper = new Helper();

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => Language::getLanguages(false),
            'id_language' => $this->context->language->id,
            'THEME_LANG_DIR' => _PS_IMG_.'l/'
        );

        return $helper->generateForm($this->getConfigForm());
    }

    protected function getConfigForm()
    {
        $fields = array();

        $fields[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Module settings'),
                'icon' => 'icon-cogs',
            ),
            'input' => array(
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => 'Disable Awesome',
                    'name' => 'C_P_AWESOME',
                    'form_group_class' => 'hide',
                    'class' => 't id-hidden',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'C_P_AWESOME_on',
                            'value' => 1,
                            'label' => 'Yes'
                        ),
                        array(
                            'id' => 'C_P_AWESOME_off',
                            'value' => 0,
                            'label' => 'No'
                        ),
                    ),
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Enable module'),
                    'desc'  => $this->l(''),
                    'name' => 'C_P_ENABLE',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'C_P_ENABLE_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'C_P_ENABLE_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Display notice to customers outside the EU'),
                    'desc'  => $this->l('Geolocation must be enabled'),
                    'name' => 'C_P_GEO',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'C_P_GEO_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'C_P_GEO_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                ),
                array(
                    'col' => 2,
                    'type' => 'text',
                    'label' => $this->l('Cookie lifetime'),
                    'desc' => $this->l('Cookie consent will be stored during this time (or until customer delete cookies)'),
                    'suffix' => 'days',
                    'name' => 'C_P_EXPIRY',
                    'class' => 't',
                    'required' => true,
                ),
                array(
                    'cols' => 113,
                    'rows' => 4,
                    'type' => 'textarea',
                    'label' => $this->l('Don\'t apply restrictions for these user agents (SEO)'),
                    'desc' => $this->l('Separate each user agent with a "|" (pipe) character'),
                    'name' => 'C_P_BOTS',
                    'class' => 't',
                ),
                array(
                    'cols' => 113,
                    'rows' => 4,
                    'type' => 'textarea',
                    'label' => $this->l('Don\'t apply restrictions for these IPs'),
                    'desc' => $this->l('Separate each IP with a "|" (pipe) character'),
                    'name' => 'C_P_IPS',
                    'class' => 't',
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Debug mode'),
                    'desc'  => $this->l(''),
                    'name' => 'C_P_DEBUG',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'C_P_DEBUG_on',
                            'value' => 1,
                            'label' => $this->l('Yes')),
                        array(
                            'id' => 'C_P_DEBUG_off',
                            'value' => 0,
                            'label' => $this->l('No')),
                    ),
                ),
                array(
                    'cols' => 113,
                    'rows' => 4,
                    'type' => 'textarea',
                    'label' => $this->l('Display only for these IPs'),
                    'desc' => $this->l('Separate each IP with a "|" (pipe) character'),
                    'name' => 'C_P_IPS_DEBUG',
                    'class' => 't',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Update settings'),
                'type' => 'submit',
                'name' => 'submitCookiesPlusModule',
            ),
        );

        $cms = CMS::listCms($this->context->language->id);
        $dummy = array(
            'id_cms' => 0,
            'meta_title' => '-'
        );

        array_unshift($cms, $dummy);

        $fields[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Basic configuration'),
                'icon' => 'icon-pencil',
            ),
            'input' => array(
                array(
                    'cols' => 90,
                    'rows' => 5,
                    'type' => 'textarea',
                    'label' => $this->l('Cookies description'),
                    'name' => 'C_P_TEXT_BASIC',
                    'lang' => true,
                    'required' => true,
                    'autoload_rte' => true,
                    'class' => 't',
                    //'autoload_rte' => version_compare(_PS_VERSION_, '1.6', '>=') ? '' : true,
                    //'class' => version_compare(_PS_VERSION_, '1.6', '>=') ? 'apc_tiny' : '',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Display a link to cookies policy CMS'),
                    'name' => 'C_P_CMS_PAGE',
                    'class' => 't',
                    'options' => array(
                        'query' => $cms,
                        'id' => 'id_cms',
                        'name' => 'meta_title'
                    ),
                ),
                /*array(
                    'col' => 9,
                    'type' => 'html',
                    'label' => 'Preview',
                    'name' => '<img style="max-width: auto;" src="'.$this->_path.'views/img/basic.png">',
                    'class' => 't',
                    'lang' => true,
                ),*/
            ),
            'submit' => array(
                'title' => $this->l('Update settings'),
                'type' => 'submit',
                'name' => 'submitCookiesPlusModule',
            ),
        );

        $fields[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Advanced configuration'),
                'icon' => 'icon-pencil',
            ),
            'input' => array(
                array(
                    'cols' => 90,
                    'rows' => 5,
                    'type' => 'textarea',
                    'label' => $this->l('Strictly necessary cookies description'),
                    'name' => 'C_P_TEXT_REQUIRED',
                    'lang' => true,
                    'required' => true,
                    'autoload_rte' => true,
                    'class' => 't',
                    //'autoload_rte' => version_compare(_PS_VERSION_, '1.6', '>=') ? '' : true,
                    //'class' => version_compare(_PS_VERSION_, '1.6', '>=') ? 'apc_tiny' : '',
                ),

                array(
                    'cols' => 90,
                    'rows' => 5,
                    'type' => 'textarea',
                    'label' => $this->l('3rd party cookies description'),
                    'name' => 'C_P_TEXT_3RDPARTY',
                    'lang' => true,
                    'required' => false,
                    'class' => 't',
                    'autoload_rte' => true,
                    //'autoload_rte' => version_compare(_PS_VERSION_, '1.6', '>=') ? '' : true,
                    //'class' => version_compare(_PS_VERSION_, '1.6', '>=') ? 'apc_tiny' : '',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Display a link to cookies policy CMS'),
                    'name' => 'C_P_CMS_PAGE_ADV',
                    'class' => 't',
                    'options' => array(
                        'query' => $cms,
                        'id' => 'id_cms',
                        'name' => 'meta_title'
                    ),
                ),
                array(
                    'type' => version_compare(_PS_VERSION_, '1.6', '>=') ? 'switch' : 'radio',
                    'label' => $this->l('Accept cookies default value'),
                    'name' => 'C_P_DEFAULT_VALUE',
                    'required' => false,
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'C_P_DEFAULT_VALUE_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'C_P_DEFAULT_VALUE_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                /*array(
                    'col' => 9,
                    'type' => 'html',
                    'label' => 'Preview',
                    'name' => '<img style="max-width: auto;" src="'.$this->_path.'views/img/advanced.png">',
                    'class' => 't',
                    'lang' => true,
                ),*/
            ),
            'submit' => array(
                'title' => $this->l('Update settings'),
                'type' => 'submit',
                'name' => 'submitCookiesPlusModule',
            ),
        );

        $fields[]['form'] = array(
            'legend' => array(
                'title' => $this->l('3rd party cookies modules'),
                'icon' => 'icon-certificate',
            ),
            'input' => array(
                array(
                    'col' => 9,
                    'type' => 'html',
                    'label' => '',
                    'name' => '<div class="alert alert-warning">'.$this->l('Select the modules that install cookies. The selected modules will not be executed until customer accepts 3rd party cookies').'</div>',
                    'class' => 't',
                    'lang' => true,
                ),
                array(
                    'col' => 9,
                    'type' => 'free',
                    'label' => $this->l('Modules blocked'),
                    'name' => 'C_P_MODULES',
                    'class' => 't',
                    'lang' => true,
                ),
                array(
                    'cols' => 113,
                    'rows' => 4,
                    'type' => 'textarea',
                    'label' => $this->l('Execute this script when 3rd party cookies are accepted'),
                    'name' => 'C_P_SCRIPT',
                    'class' => 't',
                )
            ),
            'submit' => array(
                'title' => $this->l('Update settings'),
                'type' => 'submit',
                'name' => 'submitCookiesPlusModule',
            ),
        );

        $fields[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Check compliance'),
                'icon' => 'icon-check',
            ),
            'input' => array(
                array(
                    'col' => 12,
                    'type' => 'html',
                    'label' => '',
                    'name' => $this->l('You can analyze if the module is configured correctly in the following page:').' <a target="_blank" href="https://cookiemetrix.com">https://cookiemetrix.com</a>',
                    'class' => 't',
                    'lang' => true,
                ),
                array(
                    'col' => 12,
                    'type' => 'html',
                    'label' => '',
                    'name' => '<div class="alert alert-warning">'.$this->l('Disclaimer: The creators of this module do not have a legal background. Please contact a law firm for rock solid legal advice.').'</div>',
                    'class' => 't',
                    'lang' => true,
                ),
            )
        );

        return $fields;
    }

    protected function getConfigFormValues()
    {
        $fields = array_merge($this->getConfigValues(), $this->getLangConfigValues());

        return $fields;
    }

    protected function postProcess()
    {
        $html = '';
        $errors = array();

        if (!Tools::getValue('C_P_EXPIRY')) {
            $errors[] = $this->l('You have to introduce the cookie expiry time');
        } elseif (!Validate::isUnsignedInt(Tools::getValue('C_P_EXPIRY'))
            || Tools::getValue('C_P_EXPIRY') <= 0) {
            $errors[] = $this->l('You have to introduce a correct value for cookie expiry time');
        }

        foreach (explode('|', Tools::getValue('C_P_IPS')) as $ip) {
            if ($ip && !filter_var($ip, FILTER_VALIDATE_IP)) {
                $errors[] = $ip.' '.$this->l('is not valid');
            }
        }

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $html .= $this->displayError($error);
            }
        } else {
            $fields = array_merge($this->getConfigValues(), $this->getLangConfigValues());

            foreach (array_keys($fields) as $key) {
                if ($key == 'C_P_MODULES_VALUES') {
                    Configuration::updateValue($key, Tools::jsonEncode(Tools::getValue('C_P_MODULES_VALUES')));
                } elseif ($key == 'C_P_BOTS'
                    || $key == 'C_P_IPS') {
                    $fields[$key] = trim(preg_replace('/\|+/', '|', $fields[$key]), '|');
                    Configuration::updateValue($key, $fields[$key]);
                } else {
                    Configuration::updateValue($key, $fields[$key], true);
                }
            }

            $html .= $this->displayConfirmation($this->l('Configuration saved successfully.'));
        }

        return $html;
    }

    protected function getLangConfigValues()
    {
        $fields = array();

        $configFields = array('C_P_TEXT_BASIC', 'C_P_TEXT_REQUIRED', 'C_P_TEXT_3RDPARTY');

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            foreach ($configFields as $field) {
                $fields[$field][$lang['id_lang']] = Tools::getValue(
                    $field.'_'.$lang['id_lang'],
                    Configuration::get($field, $lang['id_lang'])
                );
            }
        }

        return $fields;
    }

    protected function getConfigValues()
    {
        $fields = array();

        $configFields = array('C_P_AWESOME', 'C_P_ENABLE', 'C_P_GEO', 'C_P_DEBUG', 'C_P_EXPIRY', 'C_P_BOTS', 'C_P_IPS', 'C_P_IPS_DEBUG', 'C_P_CMS_PAGE', 'C_P_CMS_PAGE_ADV', 'C_P_DEFAULT_VALUE', 'C_P_SCRIPT');

        foreach ($configFields as $field) {
            $fields[$field] = Tools::getValue($field, Configuration::get($field));
        }

        $fields['C_P_MODULES_VALUES'] = Configuration::get('C_P_MODULES_VALUES');

        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $templateName = 'configure_modules_14.tpl';
        } else {
            $templateName = 'configure_modules.tpl';
        }

        $this->context->smarty->assign(array(
            'allModules' => $this->getModuleList('third'),
            'fieldName' => 'C_P_MODULES_VALUES'
        ));

        $fields['C_P_MODULES'] =
            $this->context->smarty->fetch($this->local_path.'views/templates/admin/'.$templateName);

        return $fields;
    }

    public function hookHeader()
    {
        if (self::executeModule()) {
            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                Tools::addJS(_PS_JS_DIR_.'jquery/jquery.fancybox-1.3.4.js');
                Tools::addCSS(_PS_CSS_DIR_.'jquery.fancybox-1.3.4.css');
            } else {
                $this->context->controller->addJqueryPlugin('fancybox');
            }

            $this->context->controller->addCSS($this->_path.'views/css/cookiesplus.css');
            if (!Configuration::get('C_P_AWESOME')) {
                $this->context->controller->addCSS($this->_path.'views/css/cookiesplus_awesome.css');
            }
            $this->context->controller->addJS($this->_path.'views/js/cookiesplus.js');

            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                $this->context->smarty->assign(array(
                    'C_P_COOKIE_VALUE'  => $this->context->cookie->psnotice,
                    'C_P_DEFAULT_VALUE' => Configuration::get('C_P_DEFAULT_VALUE'),
                    'C_P_VERSION'       => Tools::substr(_PS_VERSION_, 0, 3),
                    'C_P_THEME_NAME'    => _THEME_NAME_,
                    'C_P_CMS'           => (isset(Context::getContext()->controller->cms->id) && (Context::getContext()->controller->cms->id == Configuration::get('C_P_CMS_PAGE') || Context::getContext()->controller->cms->id == Configuration::get('C_P_CMS_PAGE_ADV'))) ? true : false
                ));
            } else {
                Media::addJsDef(array(
                    'C_P_COOKIE_VALUE'  => $this->context->cookie->psnotice,
                    'C_P_DEFAULT_VALUE' => Configuration::get('C_P_DEFAULT_VALUE'),
                    'C_P_VERSION'       => Tools::substr(_PS_VERSION_, 0, 3),
                    'C_P_CMS'           => (isset(Context::getContext()->controller->cms->id) && (Context::getContext()->controller->cms->id == Configuration::get('C_P_CMS_PAGE') || Context::getContext()->controller->cms->id == Configuration::get('C_P_CMS_PAGE_ADV'))) ? true : false
                ));
            }

            if ($this->context->cookie->psnotice == 2) {
                $this->context->smarty->assign(array(
                    'C_P_SCRIPT' => Configuration::get('C_P_SCRIPT')
                ));
            }

            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                return $this->display(__FILE__, '/views/templates/hook/script_14.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.6', '<')) {
                return $this->display(__FILE__, '/views/templates/hook/script_15.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.7', '<')) {
                return $this->display(__FILE__, 'script_16.tpl');
            } else {
                return $this->display(__FILE__, 'script_17.tpl');
            }
        }
    }

    public function hookFooter()
    {
        if (self::executeModule()) {
            $this->context->smarty->assign(array(
                'C_P_TEXT_BASIC'    => Configuration::get('C_P_TEXT_BASIC', $this->context->cookie->id_lang),
                'C_P_TEXT_REQUIRED' => Configuration::get('C_P_TEXT_REQUIRED', $this->context->cookie->id_lang),
                'C_P_TEXT_3RDPARTY' => Configuration::get('C_P_TEXT_3RDPARTY', $this->context->cookie->id_lang),
                'C_P_CMS_PAGE'      => Configuration::get('C_P_CMS_PAGE'),
                'C_P_CMS_PAGE_ADV'  => Configuration::get('C_P_CMS_PAGE_ADV'),
                'link'              => Context::getContext()->link,
            ));

            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                return $this->display(__FILE__, '/views/templates/hook/header_14.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.6', '<')) {
                return $this->display(__FILE__, '/views/templates/hook/header_15.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.7', '<')) {
                return $this->display(__FILE__, 'header_16.tpl');
            } else {
                return $this->display(__FILE__, 'header_17.tpl');
            }
        }
    }

    public function hookDisplayMobileHeader()
    {
        return $this->hookHeader();
    }

    public function hookDisplayFooterLinks()
    {
        return $this->hookDisplayFooter();
    }

    public function hookTmMegaLayoutFooter()
    {
        return $this->hookDisplayFooter();
    }

    public function hookCustomerAccount($params)
    {
        /* PS 1.4 only */
        global $smarty;
        return $this->display(__FILE__, 'views/templates/hook/customer_account_14.tpl');
    }

    public function hookDisplayMyAccountBlock()
    {
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            return $this->hookDisplayMyAccountBlockFooter();
        }
    }

    public function hookDisplayMyAccountBlockFooter()
    {
        if (Configuration::get('C_P_ENABLE')) {
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return $this->display(__FILE__, 'footer_15.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.7', '<')) {
                return $this->context->smarty->fetch($this->local_path.'views/templates/hook/footer_16.tpl');
            } else {
                return $this->context->smarty->fetch($this->local_path.'views/templates/hook/footer_17.tpl');
            }
        }
    }

    public function hookDisplayCustomerAccount()
    {
        if (Configuration::get('C_P_ENABLE')) {
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return $this->display(__FILE__, 'customer_account_15.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.7', '<')) {
                return $this->display(__FILE__, 'customer_account_16.tpl');
            } else {
                return $this->display(__FILE__, 'customer_account_17.tpl');
            }
        }
    }

    public function hookDisplayNav()
    {
        if (Configuration::get('C_P_ENABLE')) {
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return $this->display(__FILE__, 'nav_16.tpl');
            } elseif (version_compare(_PS_VERSION_, '1.7', '<')) {
                return $this->display(__FILE__, 'nav_16.tpl');
            } else {
                return $this->display(__FILE__, 'nav_17.tpl');
            }
        }
    }

    public function hookDisplayNav2()
    {
        return $this->hookDisplayNav();
    }

    public function hookDisplayTop()
    {
        return $this->hookDisplayNav();
    }

    public static function updateCookie($modules)
    {
        if (!Configuration::get('C_P_ENABLE')) {
            return $modules;
        }

        //Exclude admin calls
        if (defined('_PS_ADMIN_DIR_')) {
            return $modules;
        }
        if (is_object(Context::getContext()->controller)
            && Context::getContext()->controller->controller_type == 'admin') {
            return $modules;
        }

        //Exclude .map extensions
        $url = parse_url("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        if (pathinfo($url['path'], PATHINFO_EXTENSION) == 'map') {
            return $modules;
        }
        $url = parse_url("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        if (pathinfo($url['path'], PATHINFO_EXTENSION) == 'map') {
            return $modules;
        }

        //Validate user agent
        if (self::allowedUserAgent()) {
            return $modules;
        }

        //Validate IP
        if (self::allowedIP()) {
            return $modules;
        }

        $path = trim(Context::getContext()->shop->physical_uri, '/\\').'/';
        if ($path{0} != '/') {
            $path = '/'.$path;
        }
        $path = rawurlencode($path);
        $path = str_replace('%2F', '/', $path);
        $path = str_replace('%7E', '~', $path);

        if ((isset(Context::getContext()->cookie->psnoticeexiry)
            && Context::getContext()->cookie->psnoticeexiry
            && time() >= Context::getContext()->cookie->psnoticeexiry)
            || (!isset(Context::getContext()->cookie->psnotice)
                && !Tools::isSubmit('save')
                && !Tools::isSubmit('save-basic'))
            || (Tools::isSubmit('save')
                && !Tools::getValue('thirdparty')
                && !Tools::getValue('essential'))) {
            Context::getContext()->cookie->psnotice = null;
        }

        if (Tools::isSubmit('save-basic')) {
            Context::getContext()->cookie->psnotice = 2;
            Context::getContext()->cookie->psnoticeexiry = time() + Configuration::get('C_P_EXPIRY')*86400;
        }

        if (Tools::isSubmit('save')) {
            if (Tools::getValue('thirdparty')) {
                Context::getContext()->cookie->psnotice = 2;
            } else {
                Context::getContext()->cookie->psnotice = 1;
            }

            Context::getContext()->cookie->psnoticeexiry = time() + Configuration::get('C_P_EXPIRY')*86400;
        }

        if (!isset(Context::getContext()->cookie->psnotice)
            || !Context::getContext()->cookie->psnotice) {
            $modulesReq = array('advancedpopupcreator', 'popexit', 'newsletterpopupli', 'zonepopupnewsletter', 'homemodalwindow');
            if (is_array($modules)) {
                foreach ($modules as $key => $module) {
                    if (in_array($module['module'], $modulesReq)) {
                        unset($modules[$key]);
                    }
                }
            }
        }

        if (!isset(Context::getContext()->cookie->psnotice)
            || Context::getContext()->cookie->psnotice != '2') {
            $blockedModulesId = Configuration::get('C_P_MODULES_VALUES') ?
                Tools::jsonDecode(Configuration::get('C_P_MODULES_VALUES')) : array();

            if (is_array($modules) && is_array($blockedModulesId)) {
                foreach ($modules as $key => $module) {
                    if (in_array($module['id_module'], $blockedModulesId)) {
                        unset($modules[$key]);
                    }
                }
            }
        }

        return $modules;
    }

    public static function updateCookie14($hook_name, $hookArgs = array(), $id_module = null)
    {
        /* PS 1.4 only */
        global $cookie;

        $path = trim(__PS_BASE_URI__, '/\\').'/';
        if ($path{0} != '/') {
            $path = '/'.$path;
        }
        $path = rawurlencode($path);
        $path = str_replace('%2F', '/', $path);
        $path = str_replace('%7E', '~', $path);

        if ((isset($cookie->psnoticeexiry)
            && $cookie->psnoticeexiry
            && time() >= $cookie->psnoticeexiry)
            || (!isset($cookie->psnotice)
                && !Tools::isSubmit('save')
                && !Tools::isSubmit('save-basic'))
            || (Tools::isSubmit('save')
                && !Tools::getValue('thirdparty')
                && !Tools::getValue('essential'))) {
            $cookie->psnotice = null;
        }

        if (Tools::isSubmit('save-basic')) {
            $cookie->psnotice = 2;
            $cookie->psnoticeexiry = time() + Configuration::get('C_P_EXPIRY')*86400;
        }

        if (Tools::isSubmit('save')) {
            if (Tools::getValue('thirdparty')) {
                $cookie->psnotice = 2;
            } else {
                $cookie->psnotice = 1;
            }

            $cookie->psnoticeexiry = time() + Configuration::get('C_P_EXPIRY')*86400;
        }

        if ((!empty($id_module) && !Validate::isUnsignedId($id_module)) || !Validate::isHookName($hook_name)) {
            die(Tools::displayError());
        }

        global $cart, $cookie;
        $live_edit = false;
        if (!isset($hookArgs['cookie']) || !$hookArgs['cookie']) {
            $hookArgs['cookie'] = $cookie;
        }
        if (!isset($hookArgs['cart']) || !$hookArgs['cart']) {
            $hookArgs['cart'] = $cart;
        }
        $hook_name = Tools::strtolower($hook_name);

        if (!isset(self::$_hookModulesCache)) {
            $db = Db::getInstance(_PS_USE_SQL_SLAVE_);
            $result = $db->ExecuteS('
            SELECT h.`name` as hook, m.`id_module`, h.`id_hook`, m.`name` as module, h.`live_edit`
            FROM `'._DB_PREFIX_.'module` m
            LEFT JOIN `'._DB_PREFIX_.'hook_module` hm ON hm.`id_module` = m.`id_module`
            LEFT JOIN `'._DB_PREFIX_.'hook` h ON hm.`id_hook` = h.`id_hook`
            AND m.`active` = 1
            ORDER BY hm.`position`', false);
            self::$_hookModulesCache = array();

            if ($result) {
                while ($row = $db->nextRow()) {
                    $row['hook'] = Tools::strtolower($row['hook']);
                    if (!isset(self::$_hookModulesCache[$row['hook']])) {
                        self::$_hookModulesCache[$row['hook']] = array();
                    }

                    self::$_hookModulesCache[$row['hook']][] = array('id_hook' => $row['id_hook'], 'module' => $row['module'], 'id_module' => $row['id_module'], 'live_edit' => $row['live_edit']);
                }
            }
        }

        if (!isset(self::$_hookModulesCache[$hook_name])) {
            return;
        }

        $altern = 0;
        $output = '';
        foreach (self::$_hookModulesCache[$hook_name] as $array) {
            if (!isset($cookie->psnotice)
                || $cookie->psnotice != '2') {
                $blockedModulesId = Configuration::get('C_P_MODULES_VALUES') ?
                    Tools::jsonDecode(Configuration::get('C_P_MODULES_VALUES')) : array();
                if (is_array($blockedModulesId) && in_array($array['id_module'], $blockedModulesId)) {
                    continue;
                }
            }

            if ($id_module && $id_module != $array['id_module']) {
                continue;
            }

            if (!($moduleInstance = Module::getInstanceByName($array['module']))) {
                continue;
            }

            $exceptions = $moduleInstance->getExceptions((int)$array['id_hook'], (int)$array['id_module']);
            if (is_array($exceptions)) {
                foreach ($exceptions as $exception) {
                    if (strstr(basename($_SERVER['PHP_SELF']).'?'.$_SERVER['QUERY_STRING'], $exception['file_name'])) {
                        continue 2;
                    }
                }
            }

            if (is_callable(array($moduleInstance, 'hook'.$hook_name))) {
                $hookArgs['altern'] = ++$altern;

                $display = call_user_func(array($moduleInstance, 'hook'.$hook_name), $hookArgs);
                if ($array['live_edit'] && ((Tools::isSubmit('live_edit') && $ad = Tools::getValue('ad') && (Tools::getValue('liveToken') == sha1(Tools::getValue('ad')._COOKIE_KEY_))))) {
                    $live_edit = true;
                    $output .= '<script type="text/javascript"> modules_list.push(\''.$moduleInstance->name.'\');</script>
                                <div id="hook_'.$array['id_hook'].'_module_'.$moduleInstance->id.'_moduleName_'.$moduleInstance->name.'"
                                class="dndModule" style="border: 1px dotted red;'.(!Tools::strlen($display) ? 'height:50px;' : '').'">
                                <span><img src="'.$moduleInstance->_path.'/logo.gif">'
                                .$moduleInstance->displayName.'<span style="float:right">
                                <a href="#" id="'.$array['id_hook'].'_'.$moduleInstance->id.'" class="moveModule">
                                    <img src="'._PS_ADMIN_IMG_.'arrow_out.png"></a>
                                <a href="#" id="'.$array['id_hook'].'_'.$moduleInstance->id.'" class="unregisterHook">
                                    <img src="'._PS_ADMIN_IMG_.'delete.gif"></span></a>
                                </span>'.$display.'</div>';
                } else {
                    $output .= $display;
                }
            }
        }

        return ($live_edit ? '<script type="text/javascript">hooks_list.push(\''.$hook_name.'\'); </script><!--<div id="add_'.$hook_name.'" class="add_module_live_edit">
                <a class="exclusive" href="#">Add a module</a></div>--><div id="'.$hook_name.'" class="dndHook" style="min-height:50px">' : '').$output.($live_edit ? '</div>' : '');
    }

    public static function hookExecPayment14()
    {
        global $cart, $cookie;
        $hookArgs = array('cookie' => $cookie, 'cart' => $cart);
        $id_customer = (int)($cookie->id_customer);
        $billing = new Address((int)($cart->id_address_invoice));
        $output = '';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
        SELECT DISTINCT h.`id_hook`, m.`name`, hm.`position`
        FROM `'._DB_PREFIX_.'module_country` mc
        LEFT JOIN `'._DB_PREFIX_.'module` m ON m.`id_module` = mc.`id_module`
        INNER JOIN `'._DB_PREFIX_.'module_group` mg ON (m.`id_module` = mg.`id_module`)
        INNER JOIN `'._DB_PREFIX_.'customer_group` cg on (cg.`id_group` = mg.`id_group` AND cg.`id_customer` = '.(int)($id_customer).')
        LEFT JOIN `'._DB_PREFIX_.'hook_module` hm ON hm.`id_module` = m.`id_module`
        LEFT JOIN `'._DB_PREFIX_.'hook` h ON hm.`id_hook` = h.`id_hook`
        WHERE h.`name` = \'payment\'
        AND mc.id_country = '.(int)($billing->id_country).'
        AND m.`active` = 1
        ORDER BY hm.`position`, m.`name` DESC');
        if ($result) {
            foreach ($result as $k => $module) {
                if (!isset($cookie->psnotice)
                    || $cookie->psnotice != '2') {
                    $blockedModulesId = Configuration::get('C_P_MODULES_VALUES') ?
                        Tools::jsonDecode(Configuration::get('C_P_MODULES_VALUES')) : array();
                    if (is_array($blockedModulesId) && in_array($module['id_module'], $blockedModulesId)) {
                        continue;
                    }
                }

                if (($moduleInstance = Module::getInstanceByName($module['name'])) && is_callable(array($moduleInstance, 'hookpayment'))) {
                    if (!$moduleInstance->currencies || ($moduleInstance->currencies && sizeof(Currency::checkPaymentCurrencies($moduleInstance->id)))) {
                        $output .= call_user_func(array($moduleInstance, 'hookpayment'), $hookArgs);
                    }
                }
            }
        }
        return $output;
    }

    public static function writeCookie()
    {
        return true;
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                return '<script type="text/javascript" src="'.$this->_path.'views/js/cookiesplus.admin.js"></script>';
            } else if (version_compare(_PS_VERSION_, '1.6', '<')) {
                $this->context->controller->addJS($this->_path.'views/js/cookiesplus.admin.js');
            }
        }
    }

    public function getModuleList($type)
    {
        // Get modules directory list and memory limit
        $modules = Module::getModulesDirOnDisk();

        foreach ($modules as $key => $module) {
            if (!class_exists($module, false)) {
                // Get content from php file
                if (version_compare(_PS_VERSION_, '1.7', '<')) {
                    $file_path = _PS_MODULE_DIR_.$module.'/'.$module.'.php';
                    $file = trim(file_get_contents(_PS_MODULE_DIR_.$module.'/'.$module.'.php'));

                    if (substr($file, 0, 5) == '<?php') {
                        $file = substr($file, 5);
                    }

                    if (substr($file, -2) == '?>') {
                        $file = substr($file, 0, -2);
                    }

                    // If (false) is a trick to not load the class with "eval".
                    // This way require_once will works correctly
                    if (eval('if (false){   '.$file."\n".' }') !== false) {
                        require_once(_PS_MODULE_DIR_.$module.'/'.$module.'.php');
                    } else {
                        $errors[] = sprintf(Tools::displayError('%1$s (parse error in %2$s)'), $module, substr($file_path, strlen(_PS_ROOT_DIR_)));
                    }
                } else {
                    $file_path = _PS_MODULE_DIR_.$module.'/'.$module.'.php';
                    $file = trim(file_get_contents(_PS_MODULE_DIR_.$module.'/'.$module.'.php'));

                    try {
                        $parser = (new PhpParser\ParserFactory)->create(PhpParser\ParserFactory::PREFER_PHP7);
                        $parser->parse($file);
                        require_once($file_path);
                    } catch (PhpParser\Error $e) {
                        $errors[] = Context::getContext()->getTranslator()->trans('%1$s (parse error in %2$s)', array($module, substr($file_path, strlen(_PS_ROOT_DIR_))), 'Admin.Modules.Notification');
                    }

                    preg_match('/\n[\s\t]*?namespace\s.*?;/', $file, $ns);
                    if (!empty($ns)) {
                        $ns = preg_replace('/\n[\s\t]*?namespace\s/', '', $ns[0]);
                        $ns = rtrim($ns, ';');
                        $module = $ns.'\\'.$module;
                    }
                }

            }

            if (class_exists($module, false)) {
                try {
                    if (version_compare(_PS_VERSION_, '1.6', '<')) {
                        $tmp_module = new $module;
                    } elseif (version_compare(_PS_VERSION_, '1.7', '<')) {
                        $tmp_module = Adapter_ServiceLocator::get($module);
                    } else {
                        $serviceLocator = new PrestaShop\PrestaShop\Adapter\ServiceLocator;
                        $tmp_module = $serviceLocator::get($module);
                    }

                    $item = new \stdClass();
                    $item->name = $module;
                    $item->displayName = $tmp_module->displayName;
                    $item->id = (int)$tmp_module->id;

                    $module_list[$key] = $item;

                } catch (Exception $e) {
                }
            }
        }

        usort($module_list, function ($a, $b) { return strnatcasecmp($a->displayName, $b->displayName); });

        foreach ($module_list as $key => $module) {
            if ($module->id == 0) {
                unset($module_list[$key]);
            }

            if ($module->name == $this->name) {
                unset($module_list[$key]);
            }

            if ($type == 'third') {
                $modules_blocked = Configuration::get('C_P_MODULES_VALUES') ?
                    Tools::jsonDecode(Configuration::get('C_P_MODULES_VALUES')) : array();
            }

            if ($modules_blocked) {
                if (in_array($module->id, $modules_blocked)) {
                    $module->checked = true;
                }
            }
        }
        return $module_list;
    }

    protected static function executeModule()
    {
        //Validate allowed IPs
        if (Configuration::get('C_P_DEBUG') && !self::allowedIPDebug()) {
            return false;
        }

        //Validate user agent
        if (self::allowedUserAgent()) {
            return false;
        }

        //Validate disallow IPs
        if (self::allowedIP()) {
            return false;
        }

        if (!Configuration::get('C_P_ENABLE')) {
            return false;
        }

        if (Configuration::get('PS_GEOLOCATION_ENABLED') && Configuration::get('C_P_GEO')) {
            if (!in_array($_SERVER['SERVER_NAME'], array('localhost', '127.0.0.1'))) {
                /* Check if Maxmind Database exists */
                if (@filemtime(_PS_GEOIP_DIR_._PS_GEOIP_CITY_FILE_)) {
                    include_once(_PS_GEOIP_DIR_.'geoipcity.inc');

                    $gi = geoip_open(realpath(_PS_GEOIP_DIR_._PS_GEOIP_CITY_FILE_), GEOIP_STANDARD);
                    $record = geoip_record_by_addr($gi, Tools::getRemoteAddr());

                    if ($record->continent_code
                        && $record->continent_code != 'EU') {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    protected static function allowedUserAgent()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])
            && Configuration::get('C_P_BOTS')
            && preg_match('/'.Configuration::get('C_P_BOTS').'/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }

        return false;
    }

    protected static function allowedIP()
    {
        if (Configuration::get('C_P_IPS')) {
            if (in_array(Tools::getRemoteAddr(), explode('|', Configuration::get('C_P_IPS')))) {
                return true;
            }
        }

        return false;
    }

    protected static function allowedIPDebug()
    {
        if (!Configuration::get('C_P_IPS_DEBUG')) {
            return true;
        }

        if (in_array(Tools::getRemoteAddr(), explode('|', Configuration::get('C_P_IPS_DEBUG')))) {
            return true;
        }

        return false;
    }

    public function getWarnings($getAll = true)
    {
        $warning = array();

        if (version_compare(_PS_VERSION_, '1.5', '>=')) {
            if (Configuration::get('PS_DISABLE_NON_NATIVE_MODULE')) {
                $warning[] = $this->l('You have to enable non PrestaShop modules at ADVANCED PARAMETERS - PERFORMANCE');
            }

            if (Configuration::get('PS_DISABLE_OVERRIDES')) {
                $warning[] = $this->l('You have to enable overrides at ADVANCED PARAMETERS - PERFORMANCE');
            }
        }

        if (count($warning) && version_compare(_PS_VERSION_, '1.6.1', '<')) {
            return $warning[0];
        }

        if (count($warning) && !$getAll) {
            return $warning[0];
        }

        return $warning;
    }
}
