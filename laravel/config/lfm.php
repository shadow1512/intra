<?php

/*
|--------------------------------------------------------------------------
| Documentation for this config :
|--------------------------------------------------------------------------
| online  => http://unisharp.github.io/laravel-filemanager/config
| offline => vendor/unisharp/laravel-filemanager/docs/config.md
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
     */

    'use_package_routes'       => true,

    /*
    |--------------------------------------------------------------------------
    | Shared folder / Private folder
    |--------------------------------------------------------------------------
    |
    | If both options are set to false, then shared folder will be activated.
    |
     */

    'allow_private_folder'     => true,

    // Flexible way to customize client folders accessibility
    // If you want to customize client folders, publish tag="lfm_handler"
    // Then you can rewrite userField function in App\Handler\ConfigHandler class
    // And set 'user_field' to App\Handler\ConfigHandler::class
    // Ex: The private folder of user will be named as the user id.
    'private_folder_name'      => UniSharp\LaravelFilemanager\Handlers\ConfigHandler::class,

    'allow_shared_folder'      => true,

    'shared_folder_name'       => 'shares',

    /*
    |--------------------------------------------------------------------------
    | Folder Names
    |--------------------------------------------------------------------------
     */

    'folder_categories'        => [
        'file'  => [
            'folder_name'  => 'files',
            'startup_view' => 'grid',
            'max_size'     => 500000, // size in KB
            'valid_mime'   => [
                'application/vnd.hzn-3d-crossword','video/3gpp','video/3gpp2','application/vnd.mseq','application/vnd.3m.post-it-notes','application/vnd.3gpp.pic-bw-large','application/vnd.3gpp.pic-bw-small','application/vnd.3gpp.pic-bw-var','application/vnd.3gpp2.tcap','application/x-7z-compressed','application/x-abiword','application/x-ace-compressed','application/vnd.americandynamics.acc','application/vnd.acucobol','application/vnd.acucorp','audio/adpcm','application/x-authorware-bin','application/x-authorware-map','application/x-authorware-seg','application/vnd.adobe.air-application-installer-package+zip','application/x-shockwave-flash','application/vnd.adobe.fxp','application/pdf','application/vnd.cups-ppd','application/x-director','application/vnd.adobe.xdp+xml','application/vnd.adobe.xfdf','audio/x-aac','application/vnd.ahead.space','application/vnd.airzip.filesecure.azf','application/vnd.airzip.filesecure.azs','application/vnd.amazon.ebook','application/vnd.amiga.ami','application/andrew-inset','application/vnd.android.package-archive','application/vnd.anser-web-certificate-issue-initiation','application/vnd.anser-web-funds-transfer-initiation','application/vnd.antix.game-component','application/x-apple-diskimage','application/vnd.apple.installer+xml','application/applixware','application/vnd.hhe.lesson-player','application/vnd.aristanetworks.swi','text/x-asm','application/atomcat+xml','application/atomsvc+xml','application/atom+xml','application/pkix-attr-cert','audio/x-aiff','video/x-msvideo','application/vnd.audiograph','image/vnd.dxf','model/vnd.dwf','text/plain-bas','application/x-bcpio','application/octet-stream','image/bmp','application/x-bittorrent','application/vnd.rim.cod','application/vnd.blueice.multipass','application/vnd.bmi','application/x-sh','image/prs.btif','application/vnd.businessobjects','application/x-bzip','application/x-bzip2','application/x-csh','text/x-c','application/vnd.chemdraw+xml','text/css','chemical/x-cdx','chemical/x-cml','chemical/x-csml','application/vnd.contact.cmsg','application/vnd.claymore','application/vnd.clonk.c4group','image/vnd.dvb.subtitle','application/cdmi-capability','application/cdmi-container','application/cdmi-domain','application/cdmi-object','application/cdmi-queue','application/vnd.cluetrust.cartomobile-config','application/vnd.cluetrust.cartomobile-config-pkg','image/x-cmu-raster','model/vnd.collada+xml','text/csv','application/mac-compactpro','application/vnd.wap.wmlc','image/cgm','x-conference/x-cooltalk','image/x-cmx','application/vnd.xara','application/vnd.cosmocaller','application/x-cpio','application/vnd.crick.clicker','application/vnd.crick.clicker.keyboard','application/vnd.crick.clicker.palette','application/vnd.crick.clicker.template','application/vnd.crick.clicker.wordbank','application/vnd.criticaltools.wbs+xml','application/vnd.rig.cryptonote','chemical/x-cif','chemical/x-cmdf','application/cu-seeme','application/prs.cww','text/vnd.curl','text/vnd.curl.dcurl','text/vnd.curl.mcurl','text/vnd.curl.scurl','application/vnd.curl.car','application/vnd.curl.pcurl','application/vnd.yellowriver-custom-menu','application/dssc+der','application/dssc+xml','application/x-debian-package','audio/vnd.dece.audio','image/vnd.dece.graphic','video/vnd.dece.hd','video/vnd.dece.mobile','video/vnd.uvvu.mp4','video/vnd.dece.pd','video/vnd.dece.sd','video/vnd.dece.video','application/x-dvi','application/vnd.fdsn.seed','application/x-dtbook+xml','application/x-dtbresource+xml','application/vnd.dvb.ait','application/vnd.dvb.service','audio/vnd.digital-winds','image/vnd.djvu','application/xml-dtd','application/vnd.dolby.mlp','application/x-doom','application/vnd.dpgraph','audio/vnd.dra','application/vnd.dreamfactory','audio/vnd.dts','audio/vnd.dts.hd','image/vnd.dwg','application/vnd.dynageo','application/ecmascript','application/vnd.ecowin.chart','image/vnd.fujixerox.edmics-mmr','image/vnd.fujixerox.edmics-rlc','application/exi','application/vnd.proteus.magazine','application/epub+zip','message/rfc822','application/vnd.enliven','application/vnd.is-xpr','image/vnd.xiff','application/vnd.xfdl','application/emma+xml','application/vnd.ezpix-album','application/vnd.ezpix-package','image/vnd.fst','video/vnd.fvt','image/vnd.fastbidsheet','application/vnd.denovo.fcselayout-link','video/x-f4v','video/x-flv','image/vnd.fpx','image/vnd.net-fpx','text/vnd.fmi.flexstor','video/x-fli','application/vnd.fluxtime.clip','application/vnd.fdf','text/x-fortran','application/vnd.mif','application/vnd.framemaker','image/x-freehand','application/vnd.fsc.weblaunch','application/vnd.frogans.fnc','application/vnd.frogans.ltf','application/vnd.fujixerox.ddd','application/vnd.fujixerox.docuworks','application/vnd.fujixerox.docuworks.binder','application/vnd.fujitsu.oasys','application/vnd.fujitsu.oasys2','application/vnd.fujitsu.oasys3','application/vnd.fujitsu.oasysgp','application/vnd.fujitsu.oasysprs','application/x-futuresplash','application/vnd.fuzzysheet','image/g3fax','application/vnd.gmx','model/vnd.gtw','application/vnd.genomatix.tuxedo','application/vnd.geogebra.file','application/vnd.geogebra.tool','model/vnd.gdl','application/vnd.geometry-explorer','application/vnd.geonext','application/vnd.geoplan','application/vnd.geospace','application/x-font-ghostscript','application/x-font-bdf','application/x-gtar','application/x-texinfo','application/x-gnumeric','application/vnd.google-earth.kml+xml','application/vnd.google-earth.kmz','application/gpx+xml','application/vnd.grafeq','image/gif','text/vnd.graphviz','application/vnd.groove-account','application/vnd.groove-help','application/vnd.groove-identity-message','application/vnd.groove-injector','application/vnd.groove-tool-message','application/vnd.groove-tool-template','application/vnd.groove-vcard','video/h261','video/h263','video/h264','application/vnd.hp-hpid','application/vnd.hp-hps','application/x-hdf','audio/vnd.rip','application/vnd.hbci','application/vnd.hp-jlyt','application/vnd.hp-pcl','application/vnd.hp-hpgl','application/vnd.yamaha.hv-script','application/vnd.yamaha.hv-dic','application/vnd.yamaha.hv-voice','application/vnd.hydrostatix.sof-data','application/hyperstudio','application/vnd.hal+xml','text/html','application/vnd.ibm.rights-management','application/vnd.ibm.secure-container','text/calendar','application/vnd.iccprofile','image/x-icon','application/vnd.igloader','image/ief','application/vnd.immervision-ivp','application/vnd.immervision-ivu','application/reginfo+xml','text/vnd.in3d.3dml','text/vnd.in3d.spot','model/iges','application/vnd.intergeo','application/vnd.cinderella','application/vnd.intercon.formnet','application/vnd.isac.fcs','application/ipfix','application/pkix-cert','application/pkixcmp','application/pkix-crl','application/pkix-pkipath','application/vnd.insors.igm','application/vnd.ipunplugged.rcprofile','application/vnd.irepository.package+xml','text/vnd.sun.j2me.app-descriptor','application/java-archive','application/java-vm','application/x-java-jnlp-file','application/java-serialized-object','text/x-java-source,java','application/javascript','application/json','application/vnd.joost.joda-archive','video/jpm','image/jpeg','image/x-citrix-jpeg','image/pjpeg','video/jpeg','application/vnd.kahootz','application/vnd.chipnuts.karaoke-mmd','application/vnd.kde.karbon','application/vnd.kde.kchart','application/vnd.kde.kformula','application/vnd.kde.kivio','application/vnd.kde.kontour','application/vnd.kde.kpresenter','application/vnd.kde.kspread','application/vnd.kde.kword','application/vnd.kenameaapp','application/vnd.kidspiration','application/vnd.kinar','application/vnd.kodak-descriptor','application/vnd.las.las+xml','application/x-latex','application/vnd.llamagraphics.life-balance.desktop','application/vnd.llamagraphics.life-balance.exchange+xml','application/vnd.jam','application/vnd.lotus-1-2-3','application/vnd.lotus-approach','application/vnd.lotus-freelance','application/vnd.lotus-notes','application/vnd.lotus-organizer','application/vnd.lotus-screencam','application/vnd.lotus-wordpro','audio/vnd.lucent.voice','audio/x-mpegurl','video/x-m4v','application/mac-binhex40','application/vnd.macports.portpkg','application/vnd.osgeo.mapguide.package','application/marc','application/marcxml+xml','application/mxf','application/vnd.wolfram.player','application/mathematica','application/mathml+xml','application/mbox','application/vnd.medcalcdata','application/mediaservercontrol+xml','application/vnd.mediastation.cdkey','application/vnd.mfer','application/vnd.mfmp','model/mesh','application/mads+xml','application/mets+xml','application/mods+xml','application/metalink4+xml','application/vnd.mcd','application/vnd.micrografx.flo','application/vnd.micrografx.igx','application/vnd.eszigno3+xml','application/x-msaccess','video/x-ms-asf','application/x-msdownload','application/vnd.ms-artgalry','application/vnd.ms-cab-compressed','application/vnd.ms-ims','application/x-ms-application','application/x-msclip','image/vnd.ms-modi','application/vnd.ms-fontobject','application/vnd.ms-excel','application/vnd.ms-excel.addin.macroenabled.12','application/vnd.ms-excel.sheet.binary.macroenabled.12','application/vnd.ms-excel.template.macroenabled.12','application/vnd.ms-excel.sheet.macroenabled.12','application/vnd.ms-htmlhelp','application/x-mscardfile','application/vnd.ms-lrm','application/x-msmediaview','application/x-msmoney','application/vnd.openxmlformats-officedocument.presentationml.presentation','application/vnd.openxmlformats-officedocument.presentationml.slide','application/vnd.openxmlformats-officedocument.presentationml.slideshow','application/vnd.openxmlformats-officedocument.presentationml.template','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.spreadsheetml.template','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.openxmlformats-officedocument.wordprocessingml.template','application/x-msbinder','application/vnd.ms-officetheme','application/onenote','audio/vnd.ms-playready.media.pya','video/vnd.ms-playready.media.pyv','application/vnd.ms-powerpoint','application/vnd.ms-powerpoint.addin.macroenabled.12','application/vnd.ms-powerpoint.slide.macroenabled.12','application/vnd.ms-powerpoint.presentation.macroenabled.12','application/vnd.ms-powerpoint.slideshow.macroenabled.12','application/vnd.ms-powerpoint.template.macroenabled.12','application/vnd.ms-project','application/x-mspublisher','application/x-msschedule','application/x-silverlight-app','application/vnd.ms-pki.stl','application/vnd.ms-pki.seccat','application/vnd.visio','application/vnd.visio2013','video/x-ms-wm','audio/x-ms-wma','audio/x-ms-wax','video/x-ms-wmx','application/x-ms-wmd','application/vnd.ms-wpl','application/x-ms-wmz','video/x-ms-wmv','video/x-ms-wvx','application/x-msmetafile','application/x-msterminal','application/msword','application/vnd.ms-word.document.macroenabled.12','application/vnd.ms-word.template.macroenabled.12','application/x-mswrite','application/vnd.ms-works','application/x-ms-xbap','application/vnd.ms-xpsdocument','audio/midi','application/vnd.ibm.minipay','application/vnd.ibm.modcap','application/vnd.jcp.javame.midlet-rms','application/vnd.tmobile-livetv','application/x-mobipocket-ebook','application/vnd.mobius.mbk','application/vnd.mobius.dis','application/vnd.mobius.plc','application/vnd.mobius.mqy','application/vnd.mobius.msl','application/vnd.mobius.txf','application/vnd.mobius.daf','text/vnd.fly','application/vnd.mophun.certificate','application/vnd.mophun.application','video/mj2','audio/mpeg','video/vnd.mpegurl','video/mpeg','application/mp21','audio/mp4','video/mp4','application/mp4','application/vnd.apple.mpegurl','application/vnd.musician','application/vnd.muvee.style','application/xv+xml','application/vnd.nokia.n-gage.data','application/vnd.nokia.n-gage.symbian.install','application/x-dtbncx+xml','application/x-netcdf','application/vnd.neurolanguage.nlu','application/vnd.dna','application/vnd.noblenet-directory','application/vnd.noblenet-sealer','application/vnd.noblenet-web','application/vnd.nokia.radio-preset','application/vnd.nokia.radio-presets','text/n3','application/vnd.novadigm.edm','application/vnd.novadigm.edx','application/vnd.novadigm.ext','application/vnd.flographit','audio/vnd.nuera.ecelp4800','audio/vnd.nuera.ecelp7470','audio/vnd.nuera.ecelp9600','application/oda','application/ogg','audio/ogg','video/ogg','application/vnd.oma.dd2+xml','application/vnd.oasis.opendocument.text-web','application/oebps-package+xml','application/vnd.intu.qbo','application/vnd.openofficeorg.extension','application/vnd.yamaha.openscoreformat','audio/webm','video/webm','application/vnd.oasis.opendocument.chart','application/vnd.oasis.opendocument.chart-template','application/vnd.oasis.opendocument.database','application/vnd.oasis.opendocument.formula','application/vnd.oasis.opendocument.formula-template','application/vnd.oasis.opendocument.graphics','application/vnd.oasis.opendocument.graphics-template','application/vnd.oasis.opendocument.image','application/vnd.oasis.opendocument.image-template','application/vnd.oasis.opendocument.presentation','application/vnd.oasis.opendocument.presentation-template','application/vnd.oasis.opendocument.spreadsheet','application/vnd.oasis.opendocument.spreadsheet-template','application/vnd.oasis.opendocument.text','application/vnd.oasis.opendocument.text-master','application/vnd.oasis.opendocument.text-template','image/ktx','application/vnd.sun.xml.calc','application/vnd.sun.xml.calc.template','application/vnd.sun.xml.draw','application/vnd.sun.xml.draw.template','application/vnd.sun.xml.impress','application/vnd.sun.xml.impress.template','application/vnd.sun.xml.math','application/vnd.sun.xml.writer','application/vnd.sun.xml.writer.global','application/vnd.sun.xml.writer.template','application/x-font-otf','application/vnd.yamaha.openscoreformat.osfpvg+xml','application/vnd.osgi.dp','application/vnd.palm','text/x-pascal','application/vnd.pawaafile','application/vnd.hp-pclxl','application/vnd.picsel','image/x-pcx','image/vnd.adobe.photoshop','application/pics-rules','image/x-pict','application/x-chat','application/pkcs10','application/x-pkcs12','application/pkcs7-mime','application/pkcs7-signature','application/x-pkcs7-certreqresp','application/x-pkcs7-certificates','application/pkcs8','application/vnd.pocketlearn','image/x-portable-anymap','image/x-portable-bitmap','application/x-font-pcf','application/font-tdpfr','application/x-chess-pgn','image/x-portable-graymap','image/png','image/x-citrix-png','image/x-png','image/x-portable-pixmap','application/pskc+xml','application/vnd.ctc-posml','application/postscript','application/x-font-type1','application/vnd.powerbuilder6','application/pgp-encrypted','application/pgp-signature','application/vnd.previewsystems.box','application/vnd.pvi.ptid1','application/pls+xml','application/vnd.pg.format','application/vnd.pg.osasli','text/prs.lines.tag','application/x-font-linux-psf','application/vnd.publishare-delta-tree','application/vnd.pmi.widget','application/vnd.quark.quarkxpress','application/vnd.epson.esf','application/vnd.epson.msf','application/vnd.epson.ssf','application/vnd.epson.quickanime','application/vnd.intu.qfx','video/quicktime','application/x-rar-compressed','audio/x-pn-realaudio','audio/x-pn-realaudio-plugin','application/rsd+xml','application/vnd.rn-realmedia','application/vnd.realvnc.bed','application/vnd.recordare.musicxml','application/vnd.recordare.musicxml+xml','application/relax-ng-compact-syntax','application/vnd.data-vision.rdz','application/rdf+xml','application/vnd.cloanto.rp9','application/vnd.jisp','application/rtf','text/richtext','application/vnd.route66.link66+xml','application/rss+xml','application/shf+xml','application/vnd.sailingtracker.track','image/svg+xml','application/vnd.sus-calendar','application/sru+xml','application/set-payment-initiation','application/set-registration-initiation','application/vnd.sema','application/vnd.semd','application/vnd.semf','application/vnd.seemail','application/x-font-snf','application/scvp-vp-request','application/scvp-vp-response','application/scvp-cv-request','application/scvp-cv-response','application/sdp','text/x-setext','video/x-sgi-movie','application/vnd.shana.informed.formdata','application/vnd.shana.informed.formtemplate','application/vnd.shana.informed.interchange','application/vnd.shana.informed.package','application/thraud+xml','application/x-shar','image/x-rgb','application/vnd.epson.salt','application/vnd.accpac.simply.aso','application/vnd.accpac.simply.imp','application/vnd.simtech-mindmapper','application/vnd.commonspace','application/vnd.yamaha.smaf-audio','application/vnd.smaf','application/vnd.yamaha.smaf-phrase','application/vnd.smart.teacher','application/vnd.svd','application/sparql-query','application/sparql-results+xml','application/srgs','application/srgs+xml','application/ssml+xml','application/vnd.koan','text/sgml','application/vnd.stardivision.calc','application/vnd.stardivision.draw','application/vnd.stardivision.impress','application/vnd.stardivision.math','application/vnd.stardivision.writer','application/vnd.stardivision.writer-global','application/vnd.stepmania.stepchart','application/x-stuffit','application/x-stuffitx','application/vnd.solent.sdkm+xml','application/vnd.olpc-sugar','audio/basic','application/vnd.wqd','application/vnd.symbian.install','application/smil+xml','application/vnd.syncml+xml','application/vnd.syncml.dm+wbxml','application/vnd.syncml.dm+xml','application/x-sv4cpio','application/x-sv4crc','application/sbml+xml','text/tab-separated-values','image/tiff','application/vnd.tao.intent-module-archive','application/x-tar','application/x-tcl','application/x-tex','application/x-tex-tfm','application/tei+xml','text/plain','application/vnd.spotfire.dxp','application/vnd.spotfire.sfs','application/timestamped-data','application/vnd.trid.tpt','application/vnd.triscape.mxs','text/troff','application/vnd.trueapp','application/x-font-ttf','text/turtle','application/vnd.umajin','application/vnd.uoml+xml','application/vnd.unity','application/vnd.ufdl','text/uri-list','application/vnd.uiq.theme','application/x-ustar','text/x-uuencode','text/x-vcalendar','text/x-vcard','application/x-cdlink','application/vnd.vsf','model/vrml','application/vnd.vcx','model/vnd.mts','model/vnd.vtu','application/vnd.visionary','video/vnd.vivo','application/ccxml+xml,','application/voicexml+xml','application/x-wais-source','application/vnd.wap.wbxml','image/vnd.wap.wbmp','audio/x-wav','application/davmount+xml','application/x-font-woff','application/wspolicy+xml','image/webp','application/vnd.webturbo','application/widget','application/winhlp','text/vnd.wap.wml','text/vnd.wap.wmlscript','application/vnd.wap.wmlscriptc','application/vnd.wordperfect','application/vnd.wt.stf','application/wsdl+xml','image/x-xbitmap','image/x-xpixmap','image/x-xwindowdump','application/x-x509-ca-cert','application/x-xfig','application/xhtml+xml','application/xml','application/xcap-diff+xml','application/xenc+xml','application/patch-ops-error+xml','application/resource-lists+xml','application/rls-services+xml','application/resource-lists-diff+xml','application/xslt+xml','application/xop+xml','application/x-xpinstall','application/xspf+xml','application/vnd.mozilla.xul+xml','chemical/x-xyz','text/yaml','application/yang','application/yin+xml','application/vnd.zul','application/zip','application/vnd.handheld-entertainment+xml','application/vnd.zzazz.deck+xml'
            ],
        ],
        'image' => [
            'folder_name'  => 'photos',
            'startup_view' => 'list',
            'max_size'     => 500000, // size in KB
            'valid_mime'   => [
                'image/jpeg',
                'image/pjpeg',
                'image/png',
                'image/gif',
                'image/svg+xml',
                'application/pdf',
                'text/plain',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload / Validation
    |--------------------------------------------------------------------------
     */

    'disk'                     => 'public',

    'rename_file'              => true,

    'alphanumeric_filename'    => true,

    'alphanumeric_directory'   => true,

    'should_validate_size'     => false,

    'should_validate_mime'     => false,

    // behavior on files with identical name
    // setting it to true cause old file replace with new one
    // setting it to false show `error-file-exist` error and stop upload
    'over_write_on_duplicate'  => false,

    /*
    |--------------------------------------------------------------------------
    | Thumbnail
    |--------------------------------------------------------------------------
     */

    // If true, image thumbnails would be created during upload
    'should_create_thumbnails' => true,

    'thumb_folder_name'        => 'thumbs',

    // Create thumbnails automatically only for listed types.
    'raster_mimetypes'         => [
        'image/jpeg',
        'image/pjpeg',
        'image/png',
    ],

    'thumb_img_width'          => 200, // px

    'thumb_img_height'         => 200, // px

    /*
    |--------------------------------------------------------------------------
    | File Extension Information
    |--------------------------------------------------------------------------
     */

    'file_type_array'          => [
        'pdf'  => 'Adobe Acrobat',
        'doc'  => 'Microsoft Word',
        'docx' => 'Microsoft Word',
        'xls'  => 'Microsoft Excel',
        'xlsx' => 'Microsoft Excel',
        'zip'  => 'Archive',
        'gif'  => 'GIF Image',
        'jpg'  => 'JPEG Image',
        'jpeg' => 'JPEG Image',
        'png'  => 'PNG Image',
        'ppt'  => 'Microsoft PowerPoint',
        'pptx' => 'Microsoft PowerPoint',
    ],

    /*
    |--------------------------------------------------------------------------
    | php.ini override
    |--------------------------------------------------------------------------
    |
    | These values override your php.ini settings before uploading files
    | Set these to false to ingnore and apply your php.ini settings
    |
    | Please note that the 'upload_max_filesize' & 'post_max_size'
    | directives are not supported.
     */
    'php_ini_overrides'        => [
        'memory_limit' => '256M',
    ],
];
