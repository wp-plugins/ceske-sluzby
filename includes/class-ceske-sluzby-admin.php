<?php
// http://docs.woothemes.com/document/adding-a-section-to-a-settings-tab/
class WC_Settings_Tab_Ceske_Sluzby_Admin {

  public static function init() {
    add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 100 );
    add_action( 'woocommerce_settings_tabs_ceske-sluzby', __CLASS__ . '::settings_tab' );
    add_action( 'woocommerce_update_options_ceske-sluzby', __CLASS__ . '::update_settings' );
    add_action( 'woocommerce_sections_ceske-sluzby', __CLASS__ . '::output_sections' );
  }
  
  public static function output_sections() {
    // Neduplikovat do budoucna tuto funkci...
    global $current_section;
    $aktivace_xml = get_option( 'wc_ceske_sluzby_heureka_xml_feed-aktivace' );
    $sections = array();
    if ( $aktivace_xml == "yes" ) {
      $sections = array(
        '' => 'Základní nastavení',
        'xml-feed' => 'XML feed'
      );
    }
    if ( empty( $sections ) ) {
      return;
    }
    echo '<ul class="subsubsub">';
    $array_keys = array_keys( $sections );
    foreach ( $sections as $id => $label ) {
      echo '<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=ceske-sluzby&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
    }
    echo '</ul><br class="clear" />';
  }

  public static function add_settings_tab( $settings_tabs ) {
    $settings_tabs['ceske-sluzby'] = 'České služby';
    return $settings_tabs;
  }

  public static function settings_tab() {
    woocommerce_admin_fields( self::get_settings() );
  }

  public static function update_settings() {
    global $current_section;
    woocommerce_update_options( self::get_settings( $current_section ) );
  }

  public static function get_settings( $current_section = '' ) {
    global $current_section;

    if ( '' == $current_section ) {
    $settings = array(
      array(
        'title' => 'Služby pro WordPress',
        'type' => 'title',
        'desc' => 'Pokud nebude konkrétní hodnota vyplněna, tak se nebude příslušná služba vůbec spouštět.',
        'id' => 'wc_ceske_sluzby_title'
      ),
      array(
        'title' => 'Heureka.cz',
        'type' => 'title',
        'desc' => '',
        'id' => 'wc_ceske_sluzby_heureka_title'
      ),
      array(
        'title' => 'API klíč: Ověřeno zákazníky',
        'type' => 'text',
        'desc' => 'API klíč pro službu Ověřeno zákazníky naleznete <a href="http://sluzby.heureka.cz/sluzby/certifikat-spokojenosti/">zde</a>.',
        'id' => 'wc_ceske_sluzby_heureka_overeno-api',
        'css' => 'width: 300px'
      ),
      array(
        'title' => 'API klíč: Měření konverzí',
        'type' => 'text',
        'desc' => 'API klíč pro službu Měření konverzí naleznete <a href="http://sluzby.heureka.cz/obchody/mereni-konverzi/">zde</a>.',
        'id'   => 'wc_ceske_sluzby_heureka_konverze-api',
        'css'   => 'width: 300px'
      ),
      array(
        'title' => 'Aktivovat XML feed',
        'type' => 'checkbox',
        'desc' => 'Nastavení pro XML feed bude po aktivaci dostupné <a href="' . admin_url(). 'admin.php?page=wc-settings&tab=ceske-sluzby&section=xml-feed">zde</a>.',
        'id' => 'wc_ceske_sluzby_heureka_xml_feed-aktivace'
      ),
      array(
        'type' => 'sectionend',
        'id' => 'wc_ceske_sluzby_heureka_title'
      ),
      array(
        'title' => 'Sklik.cz',
        'type' => 'title',
        'desc' => '',
        'id' => 'wc_ceske_sluzby_sklik_title'
      ),
      array(
        'title' => 'ID konverzního kódu',
        'type' => 'text',
        'desc' => 'ID získaného kódu pro měření konverzí naleznete <a href="https://www.sklik.cz/seznam-konverzi">zde</a>. Je třeba vytvořit konverzní kód typu "vytvoření objednávky" a z něho získat potřebné ID.',
        'id' => 'wc_ceske_sluzby_sklik_konverze-objednavky'
      ),
      array(
        'type' => 'sectionend',
        'id' => 'wc_ceske_sluzby_sklik_title'
      ),
      array(
        'title' => 'Srovnáme.cz',
        'type' => 'title',
        'desc' => '',
        'id' => 'wc_ceske_sluzby_srovname_title'
      ),
      array(
        'title' => 'Identifikační klíč',
        'type' => 'text',
        'desc' => 'Identifikační klíč pro měření konverzí naleznete <a href="http://www.srovname.cz/muj-obchod">zde</a>.',
        'id' => 'wc_ceske_sluzby_srovname_konverze-objednavky'
      ),
      array(
        'type' => 'sectionend',
        'id' => 'wc_ceske_sluzby_srovname_title'
      ),
      array(
        'title' => 'Další nastavení',
        'type' => 'title',
        'desc' => '',
        'id' => 'wc_ceske_sluzby_dalsi_nastaveni_title'
      ),
      array(
        'title' => 'Možnost změny objednávek pro dobírku',
        'type' => 'checkbox',
        'desc' => 'Povolí možnost změny objednávek, které jsou provedené prostřednictvím dobírky.',
        'id' => 'wc_ceske_sluzby_dalsi_nastaveni_dobirka-zmena'
      ),
      array(
        'title' => 'Pouze doprava zdarma',
        'type' => 'checkbox',
        'desc' => 'Omezit nabídku dopravy, pokud je dostupná zdarma.',
        'id' => 'wc_ceske_sluzby_dalsi_nastaveni_doprava-pouze-zdarma'
      ),
      array(
        'type' => 'sectionend',
        'id' => 'wc_ceske_sluzby_dalsi_nastaveni_title'
      )
    );
    }
    if ( 'xml-feed' == $current_section ) {
        $settings = array(
      array(
        'title' => 'XML feed',
        'type' => 'title',
        'desc' => 'Zde budou postupně přidávána další nastavení.',
        'id' => 'wc_ceske_sluzby_xml_feed_title'
      ),
      array(
        'title' => 'Heureka.cz',
        'type' => 'title',
        'desc' => 'Generovaný feed je dostupný <a href="' . site_url() . '/?feed=heureka">zde</a>. Podrobný manuál naleznete <a href="http://sluzby.heureka.cz/napoveda/xml-feed/">zde</a>.',
        'id' => 'wc_ceske_sluzby_xml_feed_heureka_title'
      ),
      array(
        'title' => 'Dodací doba',
        'type' => 'number',
        'desc' => 'Zboží může být skladem (0), dostupné do tří dnů (1 - 3), do týdne (4 - 7), do dvou týdnů (8 - 14), do měsíce (15 - 30) či více než měsíc (31 a více).',
        'id' => 'wc_ceske_sluzby_xml_feed_heureka_dodaci_doba',
        'css' => 'width: 50px',
        'custom_attributes' => array(
          'min' => 0,
          'step' => 1
        )
      ),
      array(
        'title' => 'Podpora EAN kódů',
        'type' => 'text',
        'desc' => 'Pokud doplňujete EAN kód do pole SKU, tak zadejte hodnotu "SKU" (bez uvozovek). Podpora pro další možnosti zadávání EAN (uživatelská pole) bude doplněna.',
        'id' => 'wc_ceske_sluzby_xml_feed_heureka_podpora_ean',
        'css' => 'width: 100px',
      ),
      array(
        'type' => 'sectionend',
        'id' => 'wc_ceske_sluzby_xml_feed_heureka_title'
      )
      );
    }
    return $settings;
  }
}