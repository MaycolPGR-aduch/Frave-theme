<?php
/**
 * Fragrance notes: the ingredients a perfume is described with (bergamot, rose, oud…), with
 * their olfactory family (inc/fragrance-families.php), the level where they usually show
 * (salida, corazón, fondo: inc/fragrance-pyramid.php), a short description written for the
 * site from general perfumery knowledge, their line icon (inc/fragrance-note-icons.php) and
 * the names they are written with in the perfumery tops (aliases).
 *
 * Each perfumery subcategory shows its notes (template-parts/components/fragrance-notes.php,
 * right after its top): the notes of its published top references, in the level where they
 * appear most, plus the hallmarks of the style («Sellos del estilo», frave_style_seals()),
 * which may come from no reference at all.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Every note: slug => name, family, level (top, heart, base), text, icon and aliases (lower
 * case, without accents, as the tops may write them).
 *
 * @return array<string, array{name: string, family: string, level: string, text: string, icon: string, aliases?: string[]}>
 */
function frave_fragrance_notes(): array {
	return array(
		// Cítrica.
		'bergamota'           => array(
			'name'   => __( 'Bergamota', 'frave' ),
			'family' => 'citrica',
			'level'  => 'top',
			'icon'   => 'citrus-slice',
			'text'   => __( 'Cítrico verde y luminoso, con un fondo floral. Abre la gran mayoría de los perfumes y les da frescura elegante.', 'frave' ),
		),
		'mandarina'           => array(
			'name'   => __( 'Mandarina', 'frave' ),
			'family' => 'citrica',
			'level'  => 'top',
			'icon'   => 'citrus',
			'text'   => __( 'Cítrico dulce y jugoso, más suave que la naranja. Aporta alegría y una salida amable.', 'frave' ),
		),
		'naranja'             => array(
			'name'    => __( 'Naranja', 'frave' ),
			'family'  => 'citrica',
			'level'   => 'top',
			'icon'    => 'citrus',
			'text'    => __( 'Fresca y soleada, de la cáscara del fruto. Da una salida brillante y fácil de reconocer.', 'frave' ),
			'aliases' => array( 'naranja de sicilia', 'naranja dulce' ),
		),
		'pomelo'              => array(
			'name'    => __( 'Pomelo', 'frave' ),
			'family'  => 'citrica',
			'level'   => 'top',
			'icon'    => 'citrus-slice',
			'text'    => __( 'Cítrico chispeante y ligeramente amargo. Muy usado en perfumes frescos y deportivos.', 'frave' ),
			'aliases' => array( 'toronja' ),
		),
		'limon'               => array(
			'name'   => __( 'Limón', 'frave' ),
			'family' => 'citrica',
			'level'  => 'top',
			'icon'   => 'citrus-slice',
			'text'   => __( 'Ácido, limpio y vibrante. Es la imagen misma de la frescura, aunque se evapora pronto.', 'frave' ),
		),
		'cidra'               => array(
			'name'   => __( 'Cidra', 'frave' ),
			'family' => 'citrica',
			'level'  => 'top',
			'icon'   => 'citrus-slice',
			'text'   => __( 'Antepasado del limón, de cáscara gruesa y aroma más seco y elegante.', 'frave' ),
		),
		// Floral.
		'jazmin'              => array(
			'name'    => __( 'Jazmín', 'frave' ),
			'family'  => 'floral',
			'level'   => 'heart',
			'icon'    => 'jasmine',
			'text'    => __( 'Flor blanca intensa, sensual y ligeramente afrutada. Una de las materias más importantes de la perfumería fina.', 'frave' ),
			'aliases' => array( 'jazmin sambac' ),
		),
		'rosa'                => array(
			'name'   => __( 'Rosa', 'frave' ),
			'family' => 'floral',
			'level'  => 'heart',
			'icon'   => 'rose',
			'text'   => __( 'La flor clásica de la perfumería: aterciopelada, romántica y versátil, de lo fresco a lo profundo.', 'frave' ),
		),
		'rosa-de-taif'        => array(
			'name'    => __( 'Rosa de Taif', 'frave' ),
			'family'  => 'floral',
			'level'   => 'heart',
			'icon'    => 'rose',
			'text'    => __( 'Rosa de las montañas de Arabia, intensa y especiada. Junto al oud y el azafrán forma el trío clásico de la perfumería árabe.', 'frave' ),
			'aliases' => array( 'rosa damascena' ),
		),
		'flores-blancas'      => array(
			'name'   => __( 'Flores blancas', 'frave' ),
			'family' => 'floral',
			'level'  => 'heart',
			'icon'   => 'blossoms',
			'text'   => __( 'Ramillete de flores como jazmín, azahar y tuberosa: cremoso, luminoso y envolvente.', 'frave' ),
		),
		'iris'                => array(
			'name'   => __( 'Iris', 'frave' ),
			'family' => 'floral',
			'level'  => 'heart',
			'icon'   => 'iris',
			'text'   => __( 'De su raíz se obtiene una nota empolvada, elegante y algo terrosa; una de las materias más preciadas.', 'frave' ),
		),
		'neroli'              => array(
			'name'   => __( 'Neroli', 'frave' ),
			'family' => 'floral',
			'level'  => 'heart',
			'icon'   => 'orange-blossom',
			'text'   => __( 'Esencia de la flor del naranjo amargo: fresca, verde y luminosa, entre lo floral y lo cítrico.', 'frave' ),
		),
		'azahar'              => array(
			'name'    => __( 'Azahar', 'frave' ),
			'family'  => 'floral',
			'level'   => 'heart',
			'icon'    => 'orange-blossom',
			'text'    => __( 'La flor del naranjo en su versión más dulce y melosa. Transmite limpieza y calidez a la vez.', 'frave' ),
			'aliases' => array( 'flor de azahar', 'flor de naranjo' ),
		),
		'ylang-ylang'         => array(
			'name'   => __( 'Ylang-ylang', 'frave' ),
			'family' => 'floral',
			'level'  => 'heart',
			'icon'   => 'ylang',
			'text'   => __( 'Flor tropical cremosa y exótica, con matices de plátano y especias. Da cuerpo a los bouquets florales.', 'frave' ),
		),
		'lirio-de-los-valles' => array(
			'name'    => __( 'Lirio de los valles', 'frave' ),
			'family'  => 'floral',
			'level'   => 'heart',
			'icon'    => 'lily-valley',
			'text'    => __( 'También llamado muguet: floral verde, fresco y limpio, asociado a la primavera.', 'frave' ),
			'aliases' => array( 'muguet' ),
		),
		'lirio'               => array(
			'name'   => __( 'Lirio', 'frave' ),
			'family' => 'floral',
			'level'  => 'heart',
			'icon'   => 'lily',
			'text'   => __( 'Flor blanca de aroma fresco y especiado, más luminoso que el de las flores cremosas.', 'frave' ),
		),
		'violeta'             => array(
			'name'   => __( 'Violeta', 'frave' ),
			'family' => 'floral',
			'level'  => 'heart',
			'icon'   => 'violet',
			'text'   => __( 'Floral empolvado y dulce, con un aire nostálgico; sus hojas, en cambio, huelen verde.', 'frave' ),
		),
		'orquidea'            => array(
			'name'   => __( 'Orquídea', 'frave' ),
			'family' => 'floral',
			'level'  => 'heart',
			'icon'   => 'orchid',
			'text'   => __( 'Acorde floral exótico y cremoso: la orquídea casi no huele, así que el perfumista la recrea.', 'frave' ),
		),
		'heliotropo'          => array(
			'name'   => __( 'Heliotropo', 'frave' ),
			'family' => 'floral',
			'level'  => 'heart',
			'icon'   => 'heliotrope',
			'text'   => __( 'Florecillas que huelen a almendra y vainilla: un floral dulce y empolvado.', 'frave' ),
		),
		'manzanilla'          => array(
			'name'   => __( 'Manzanilla', 'frave' ),
			'family' => 'floral',
			'level'  => 'heart',
			'icon'   => 'daisy',
			'text'   => __( 'Herbal, suave y algo afrutada. Aporta calma y un toque natural.', 'frave' ),
		),
		// Frutal.
		'frutos-rojos'        => array(
			'name'    => __( 'Frutos rojos', 'frave' ),
			'family'  => 'frutal',
			'level'   => 'top',
			'icon'    => 'berries',
			'text'    => __( 'Frambuesa, fresa y grosella: dulces, jugosos y juveniles.', 'frave' ),
			'aliases' => array( 'frambuesa', 'fresa', 'grosella' ),
		),
		'manzana'             => array(
			'name'   => __( 'Manzana', 'frave' ),
			'family' => 'frutal',
			'level'  => 'top',
			'icon'   => 'apple',
			'text'   => __( 'Fruta crujiente y jugosa que da frescura dulce a la salida.', 'frave' ),
		),
		'pera'                => array(
			'name'   => __( 'Pera', 'frave' ),
			'family' => 'frutal',
			'level'  => 'top',
			'icon'   => 'pear',
			'text'   => __( 'Fruta acuosa y delicada, muy usada en perfumes femeninos frescos y gourmand.', 'frave' ),
		),
		'pina'                => array(
			'name'   => __( 'Piña', 'frave' ),
			'family' => 'frutal',
			'level'  => 'top',
			'icon'   => 'pineapple',
			'text'   => __( 'Tropical, ácida y dulce. En perfumería masculina aporta un brillo frutal y moderno.', 'frave' ),
		),
		'frutas-tropicales'   => array(
			'name'    => __( 'Frutas tropicales', 'frave' ),
			'family'  => 'frutal',
			'level'   => 'heart',
			'icon'    => 'mango',
			'text'    => __( 'Mango, maracuyá y frutas exóticas: dulzor jugoso y soleado.', 'frave' ),
			'aliases' => array( 'mango', 'maracuya' ),
		),
		'acorde-frutal'       => array(
			'name'    => __( 'Acorde frutal', 'frave' ),
			'family'  => 'frutal',
			'level'   => 'heart',
			'icon'    => 'cherries',
			'text'    => __( 'Mezcla de frutas maduras (melocotón, albaricoque, ciruela) que da un corazón dulce y alegre.', 'frave' ),
			'aliases' => array( 'frutas', 'notas frutales' ),
		),
		// Amaderada.
		'oud'                 => array(
			'name'    => __( 'Oud', 'frave' ),
			'family'  => 'amaderada',
			'level'   => 'base',
			'icon'    => 'oud',
			'text'    => __( 'Madera resinosa del árbol de agar: profunda, ahumada y algo animal. Es el alma de la perfumería árabe.', 'frave' ),
			'aliases' => array( 'agarwood', 'madera de agar' ),
		),
		'pachuli'             => array(
			'name'   => __( 'Pachulí', 'frave' ),
			'family' => 'amaderada',
			'level'  => 'base',
			'icon'   => 'patchouli',
			'text'   => __( 'Hoja terrosa, húmeda y profunda. Da carácter y fijación a los fondos amaderados y orientales.', 'frave' ),
		),
		'musgo-de-roble'      => array(
			'name'    => __( 'Musgo de roble', 'frave' ),
			'family'  => 'amaderada',
			'level'   => 'base',
			'icon'    => 'moss',
			'text'    => __( 'Verde, húmedo y algo salado, como un bosque. Es la base de los perfumes chipre.', 'frave' ),
			'aliases' => array( 'musgo' ),
		),
		'sandalo'             => array(
			'name'   => __( 'Sándalo', 'frave' ),
			'family' => 'amaderada',
			'level'  => 'base',
			'icon'   => 'sandalwood',
			'text'   => __( 'Madera cremosa, suave y lechosa. Envuelve el fondo y alarga la estela.', 'frave' ),
		),
		'cedro'               => array(
			'name'   => __( 'Cedro', 'frave' ),
			'family' => 'amaderada',
			'level'  => 'base',
			'icon'   => 'cedar',
			'text'   => __( 'Madera seca y limpia, como el lápiz recién afilado. Da estructura a muchos perfumes.', 'frave' ),
		),
		'vetiver'             => array(
			'name'    => __( 'Vetiver', 'frave' ),
			'family'  => 'amaderada',
			'level'   => 'base',
			'icon'    => 'vetiver',
			'text'    => __( 'Raíz de una hierba tropical: terrosa, ahumada y elegante.', 'frave' ),
			'aliases' => array( 'vetiver ahumado' ),
		),
		'guayaco'             => array(
			'name'    => __( 'Guayaco', 'frave' ),
			'family'  => 'amaderada',
			'level'   => 'base',
			'icon'    => 'log',
			'text'    => __( 'Madera ahumada y suave, con un matiz dulce que recuerda al alquitrán y la rosa.', 'frave' ),
			'aliases' => array( 'madera de guayaco' ),
		),
		'maderas'             => array(
			'name'    => __( 'Maderas', 'frave' ),
			'family'  => 'amaderada',
			'level'   => 'base',
			'icon'    => 'log',
			'text'    => __( 'Acorde de maderas secas o cremosas que sostiene el perfume y lo hace durar.', 'frave' ),
			'aliases' => array( 'maderas cremosas', 'notas amaderadas', 'madera' ),
		),
		'madera-de-ambar'     => array(
			'name'    => __( 'Madera de ámbar', 'frave' ),
			'family'  => 'amaderada',
			'level'   => 'base',
			'icon'    => 'amber-wood',
			'text'    => __( 'Molécula moderna, amaderada y ambarada a la vez, muy difusiva: deja una estela que se nota a distancia.', 'frave' ),
			'aliases' => array( 'amberwood' ),
		),
		'resina-de-abeto'     => array(
			'name'   => __( 'Resina de abeto', 'frave' ),
			'family' => 'amaderada',
			'level'  => 'base',
			'icon'   => 'fir',
			'text'   => __( 'Balsámica y algo dulce, con el frescor resinoso de los bosques de coníferas.', 'frave' ),
		),
		'cuero'               => array(
			'name'   => __( 'Cuero', 'frave' ),
			'family' => 'amaderada',
			'level'  => 'heart',
			'icon'   => 'leather',
			'text'   => __( 'Acorde ahumado y suave que recuerda a la piel curtida. Da carácter y sofisticación.', 'frave' ),
		),
		// Oriental.
		'ambar'               => array(
			'name'   => __( 'Ámbar', 'frave' ),
			'family' => 'oriental',
			'level'  => 'base',
			'icon'   => 'amber',
			'text'   => __( 'Acorde cálido y resinoso de ládano, benjuí y vainilla. Envuelve y da duración.', 'frave' ),
		),
		'almizcle'            => array(
			'name'    => __( 'Almizcle', 'frave' ),
			'family'  => 'oriental',
			'level'   => 'base',
			'icon'    => 'musk',
			'text'    => __( 'Suave, limpio y envolvente, como piel recién lavada. Fija el perfume y alarga su estela.', 'frave' ),
			'aliases' => array( 'almizcle blanco', 'musk' ),
		),
		'benjui'              => array(
			'name'   => __( 'Benjuí', 'frave' ),
			'family' => 'oriental',
			'level'  => 'base',
			'icon'   => 'resin',
			'text'   => __( 'Resina balsámica con notas de vainilla y canela; endulza y calienta el fondo.', 'frave' ),
		),
		'ambar-gris'          => array(
			'name'   => __( 'Ámbar gris', 'frave' ),
			'family' => 'oriental',
			'level'  => 'base',
			'icon'   => 'ambergris',
			'text'   => __( 'Nota marina, salina y animal, hoy recreada con moléculas modernas. Da un brillo mineral al fondo.', 'frave' ),
		),
		'tabaco'              => array(
			'name'   => __( 'Tabaco', 'frave' ),
			'family' => 'oriental',
			'level'  => 'base',
			'icon'   => 'tobacco',
			'text'   => __( 'Hoja seca, dulce y melosa. Aporta calidez y un aire clásico y masculino.', 'frave' ),
		),
		'ambroxan'            => array(
			'name'   => __( 'Ambroxan', 'frave' ),
			'family' => 'oriental',
			'level'  => 'base',
			'icon'   => 'molecule',
			'text'   => __( 'Molécula ambarada y salina, limpia y muy duradera. Es la firma de muchos perfumes modernos.', 'frave' ),
		),
		'ladano'              => array(
			'name'    => __( 'Ládano', 'frave' ),
			'family'  => 'oriental',
			'level'   => 'base',
			'icon'    => 'resin',
			'text'    => __( 'Resina de la jara, cálida y algo cuero: el corazón de los acordes de ámbar.', 'frave' ),
			'aliases' => array( 'labdano' ),
		),
		'incienso'            => array(
			'name'    => __( 'Incienso', 'frave' ),
			'family'  => 'oriental',
			'level'   => 'base',
			'icon'    => 'incense',
			'text'    => __( 'Resina de olíbano: humo cálido, especiado y místico. Muy presente en la tradición árabe.', 'frave' ),
			'aliases' => array( 'olibano' ),
		),
		// Aromática.
		'lavanda'             => array(
			'name'   => __( 'Lavanda', 'frave' ),
			'family' => 'aromatica',
			'level'  => 'top',
			'icon'   => 'lavender',
			'text'   => __( 'Herbal, limpia y relajante. Es la base de los perfumes fougère y del aroma a limpio.', 'frave' ),
		),
		'menta'               => array(
			'name'   => __( 'Menta', 'frave' ),
			'family' => 'aromatica',
			'level'  => 'top',
			'icon'   => 'mint',
			'text'   => __( 'Fresca y vigorizante, da un golpe de frío en la salida.', 'frave' ),
		),
		'hoja-de-laurel'      => array(
			'name'    => __( 'Hoja de laurel', 'frave' ),
			'family'  => 'aromatica',
			'level'   => 'heart',
			'icon'    => 'bay-leaf',
			'text'    => __( 'Aromática y ligeramente especiada; aporta un frescor mediterráneo.', 'frave' ),
			'aliases' => array( 'laurel' ),
		),
		'salvia'              => array(
			'name'    => __( 'Salvia', 'frave' ),
			'family'  => 'aromatica',
			'level'   => 'heart',
			'icon'    => 'sage',
			'text'    => __( 'Hierba aromática, algo almizclada y terrosa; muy usada en perfumería masculina.', 'frave' ),
			'aliases' => array( 'salvia esclarea' ),
		),
		'enebro'              => array(
			'name'   => __( 'Enebro', 'frave' ),
			'family' => 'aromatica',
			'level'  => 'top',
			'icon'   => 'juniper',
			'text'   => __( 'Bayas resinosas y frescas, con el carácter seco de la ginebra.', 'frave' ),
		),
		'te-negro'            => array(
			'name'    => __( 'Té negro', 'frave' ),
			'family'  => 'aromatica',
			'level'   => 'base',
			'icon'    => 'tea',
			'text'    => __( 'Ahumado, seco y elegante, con un fondo tostado.', 'frave' ),
			'aliases' => array( 'te' ),
		),
		// Gourmand.
		'vainilla'            => array(
			'name'    => __( 'Vainilla', 'frave' ),
			'family'  => 'gourmand',
			'level'   => 'base',
			'icon'    => 'vanilla',
			'text'    => __( 'Dulce, cremosa y reconfortante. Es el fondo más querido de la perfumería y da calidez a cualquier composición.', 'frave' ),
			'aliases' => array( 'vainilla bourbon', 'vainilla de madagascar' ),
		),
		'haba-tonka'          => array(
			'name'    => __( 'Haba tonka', 'frave' ),
			'family'  => 'gourmand',
			'level'   => 'base',
			'icon'    => 'tonka',
			'text'    => __( 'Semilla con aroma a almendra, vainilla y heno. Endulza y suaviza el fondo.', 'frave' ),
			'aliases' => array( 'tonka' ),
		),
		'praline'             => array(
			'name'   => __( 'Praliné', 'frave' ),
			'family' => 'gourmand',
			'level'  => 'heart',
			'icon'   => 'praline',
			'text'   => __( 'Almendras o avellanas caramelizadas: tostado, dulce y adictivo.', 'frave' ),
		),
		'caramelo'            => array(
			'name'    => __( 'Caramelo', 'frave' ),
			'family'  => 'gourmand',
			'level'   => 'heart',
			'icon'    => 'candy',
			'text'    => __( 'Azúcar fundida y toffee: dulzor cálido y goloso.', 'frave' ),
			'aliases' => array( 'toffee' ),
		),
		'cafe'                => array(
			'name'    => __( 'Café', 'frave' ),
			'family'  => 'gourmand',
			'level'   => 'heart',
			'icon'    => 'coffee',
			'text'    => __( 'Grano tostado, amargo y envolvente; muy presente en los gourmand árabes.', 'frave' ),
			'aliases' => array( 'cafe arabica' ),
		),
		'miel'                => array(
			'name'   => __( 'Miel', 'frave' ),
			'family' => 'gourmand',
			'level'  => 'base',
			'icon'   => 'honey',
			'text'   => __( 'Dulce, cálida y algo animal. Da redondez y sensualidad al fondo.', 'frave' ),
		),
		'coco'                => array(
			'name'   => __( 'Coco', 'frave' ),
			'family' => 'gourmand',
			'level'  => 'heart',
			'icon'   => 'coconut',
			'text'   => __( 'Cremoso y solar, evoca el verano y la piel bronceada.', 'frave' ),
		),
		'crema-batida'        => array(
			'name'    => __( 'Crema batida', 'frave' ),
			'family'  => 'gourmand',
			'level'   => 'heart',
			'icon'    => 'cream',
			'text'    => __( 'Acorde lácteo, suave y aireado, que hace al perfume tierno y goloso.', 'frave' ),
			'aliases' => array( 'crema' ),
		),
		'frutos-secos'        => array(
			'name'    => __( 'Frutos secos', 'frave' ),
			'family'  => 'gourmand',
			'level'   => 'heart',
			'icon'    => 'almond',
			'text'    => __( 'Almendras y avellanas confitadas: tostadas y dulces.', 'frave' ),
			'aliases' => array( 'frutos secos confitados', 'almendra' ),
		),
		'datil'               => array(
			'name'    => __( 'Dátil', 'frave' ),
			'family'  => 'gourmand',
			'level'   => 'heart',
			'icon'    => 'date',
			'text'    => __( 'Fruto dulce y meloso del desierto, compañero del café y las especias en los gourmand árabes.', 'frave' ),
			'aliases' => array( 'datiles' ),
		),
		// Fresca.
		'notas-marinas'       => array(
			'name'    => __( 'Notas marinas', 'frave' ),
			'family'  => 'fresca',
			'level'   => 'top',
			'icon'    => 'waves',
			'text'    => __( 'Brisa de mar, sal y agua: el frescor de los perfumes acuáticos.', 'frave' ),
			'aliases' => array( 'sal mineral', 'notas acuaticas', 'sal marina' ),
		),
		'hojas-verdes'        => array(
			'name'    => __( 'Hojas verdes', 'frave' ),
			'family'  => 'fresca',
			'level'   => 'top',
			'icon'    => 'leaves',
			'text'    => __( 'Hojas recién cortadas: frescor vegetal y natural.', 'frave' ),
			'aliases' => array( 'hoja de violeta', 'notas verdes' ),
		),
		'aldehidos'           => array(
			'name'   => __( 'Aldehídos', 'frave' ),
			'family' => 'fresca',
			'level'  => 'top',
			'icon'   => 'sparkle',
			'text'   => __( 'Moléculas chispeantes con aire a jabón y burbujas; hicieron famosos a los florales clásicos.', 'frave' ),
		),
		// Especiada.
		'canela'              => array(
			'name'   => __( 'Canela', 'frave' ),
			'family' => 'especiada',
			'level'  => 'heart',
			'icon'   => 'cinnamon',
			'text'   => __( 'Especia dulce y picante que calienta la composición desde el primer momento.', 'frave' ),
		),
		'jengibre'            => array(
			'name'   => __( 'Jengibre', 'frave' ),
			'family' => 'especiada',
			'level'  => 'top',
			'icon'   => 'ginger',
			'text'   => __( 'Raíz picante y fresca, con un toque cítrico.', 'frave' ),
		),
		'cardamomo'           => array(
			'name'   => __( 'Cardamomo', 'frave' ),
			'family' => 'especiada',
			'level'  => 'top',
			'icon'   => 'cardamom',
			'text'   => __( 'Especia fresca y aromática, típica del café árabe.', 'frave' ),
		),
		'pimienta-rosa'       => array(
			'name'   => __( 'Pimienta rosa', 'frave' ),
			'family' => 'especiada',
			'level'  => 'top',
			'icon'   => 'pepper',
			'text'   => __( 'Más afrutada que picante, da una chispa moderna a la salida.', 'frave' ),
		),
		'pimienta-negra'      => array(
			'name'    => __( 'Pimienta negra', 'frave' ),
			'family'  => 'especiada',
			'level'   => 'top',
			'icon'    => 'pepper',
			'text'    => __( 'Seca y picante, aporta nervio y frescura especiada.', 'frave' ),
			'aliases' => array( 'pimienta' ),
		),
		'azafran'             => array(
			'name'   => __( 'Azafrán', 'frave' ),
			'family' => 'especiada',
			'level'  => 'heart',
			'icon'   => 'saffron',
			'text'   => __( 'Especia preciosa, entre el cuero y la miel, de borde dorado y algo amargo. Inseparable del oud y la rosa.', 'frave' ),
		),
	);
}

/**
 * Hallmarks of each style: notes that characterize it, shown with «Sello del estilo» even
 * when no top reference carries them. Category slug => note slugs.
 *
 * @return array<string, string[]>
 */
function frave_style_seals(): array {
	return (array) apply_filters(
		'frave_style_seals',
		array(
			'perfumeria-arabe'     => array( 'oud', 'rosa-de-taif', 'azafran', 'incienso', 'ambar', 'almizcle', 'datil' ),
			'perfumeria-disenador' => array( 'bergamota', 'lavanda', 'notas-marinas', 'haba-tonka', 'cuero' ),
			'perfumeria-exclusiva' => array( 'oud', 'iris', 'cuero', 'incienso', 'azafran' ),
		)
	);
}

/** A note written as in the tops («Café arábica») turned into its slug, or ''. */
function frave_note_slug( string $text ): string {
	static $index = null;
	if ( null === $index ) {
		$index = array();
		foreach ( frave_fragrance_notes() as $slug => $note ) {
			foreach ( array_merge( array( $note['name'] ), $note['aliases'] ?? array() ) as $name ) {
				$index[ frave_note_key( $name ) ] = $slug;
			}
		}
	}
	return $index[ frave_note_key( $text ) ] ?? '';
}

function frave_note_key( string $text ): string {
	return trim( preg_replace( '/\s+/', ' ', strtolower( remove_accents( $text ) ) ) );
}

/**
 * The notes of a category, by level: those of its published top references (in the level
 * where they appear most) and its hallmarks. Each one with the perfumes that carry it and
 * whether it is a hallmark; hallmarks first (in the style's order), then the most frequent.
 *
 * @return array<string, array<int, array{slug: string, note: array, perfumes: string[], seal: bool}>>
 */
function frave_category_notes( WP_Term $category ): array {
	$notes = frave_fragrance_notes();
	$found = array();
	foreach ( function_exists( 'frave_top_entries' ) ? frave_top_entries( $category ) : array() as $entry ) {
		$data = frave_top_entry( $entry );
		foreach ( $data['notes'] as $level => $list ) {
			foreach ( $list as $text ) {
				$slug = frave_note_slug( $text );
				if ( '' === $slug ) {
					continue;
				}
				$found[ $slug ]['levels'][ $level ]          = ( $found[ $slug ]['levels'][ $level ] ?? 0 ) + 1;
				$found[ $slug ]['perfumes'][ $data['name'] ] = true;
			}
		}
	}
	$seals = array_values( array_intersect( frave_style_seals()[ $category->slug ] ?? array(), array_keys( $notes ) ) );
	foreach ( $seals as $slug ) {
		$found[ $slug ] = $found[ $slug ] ?? array();
	}

	$tiers = array_fill_keys( array( 'top', 'heart', 'base' ), array() );
	foreach ( $found as $slug => $info ) {
		$levels = $info['levels'] ?? array();
		arsort( $levels );
		$level             = $levels ? (string) array_key_first( $levels ) : $notes[ $slug ]['level'];
		$tiers[ $level ][] = array(
			'slug'     => $slug,
			'note'     => $notes[ $slug ],
			'perfumes' => array_keys( $info['perfumes'] ?? array() ),
			'seal'     => in_array( $slug, $seals, true ),
		);
	}
	// Hallmarks first, in the style's order; then the notes in more perfumes, then by name.
	$rank = array_flip( $seals );
	foreach ( $tiers as &$items ) {
		usort(
			$items,
			static fn( array $a, array $b ): int => array( $rank[ $a['slug'] ] ?? PHP_INT_MAX, count( $b['perfumes'] ), $a['note']['name'] ) <=> array( $rank[ $b['slug'] ] ?? PHP_INT_MAX, count( $a['perfumes'] ), $b['note']['name'] )
		);
	}
	unset( $items );
	return array_filter( $tiers );
}

/** The notes of a perfumery subcategory, after its top. */
function frave_detail_notes(): void {
	if ( is_paged() || frave_catalog_active_filter_count() > 0 ) {
		return;
	}
	$term = frave_detail_term();
	if ( $term ) {
		get_template_part( 'template-parts/components/fragrance-notes', null, array( 'category' => $term ) );
	}
}
add_action( 'woocommerce_before_shop_loop', 'frave_detail_notes', -1 );
add_action( 'woocommerce_no_products_found', 'frave_detail_notes', -1 );
