<?php
define('PROYECTO', '0'); // 0 = LOCAL, 1 = PRODUCCION

if (PROYECTO == '0') {
    // LOCAL
    define('URL','http://localhost/eticket/');
    define('HOST','localhost');
    define('DB','eticket');
    define('USER','root');
    define('PASSWORD','');
    define('IPHOST_REBSOL','192.168.1.18');
    define('DBNAME_REBSOL','IOPA');
    define('CHARSET_REBSOL','utf8');
    define('USER_REBSOL','lfarias');
    define('PW_REBSOL','iopa2022$');
} else {
    // PRODUCCIÓN
    define('URL','http://190.114.253.112/eticket/');
    define('HOST','190.114.253.112');
    define('DB','eticket');
    define('USER','tecadmin');
    define('PASSWORD','NSloteria2015');
    define('IPHOST_REBSOL','dnsiopa.fortiddns.com:3306'); //acceso al .15
    define('DBNAME_REBSOL','IOPA');
    define('CHARSET_REBSOL','utf8');
    define('USER_REBSOL','rebsolll');
    define('PW_REBSOL','iopa2019');
}

define('CHARSET','utf8mb4');
define('CORREO','soporte@iopa.cl');
define('PWCORREO','P&[03IeFu@sw');




  //ANTIGUOS
  //define('URL','http://localhost/eticket/');
  //define('HOST','localhost');
  //define('DB','eticket');
  //define('USER','root');
  //define('PASSWORD','');
  //define('CHARSET','utf8mb4');
  ////define('CORREO','soporte1@iopa.cl');
  ////define('PWCORREO','uv5s-i^gcJDL');
  //define('CORREO','soporte@iopa.cl');
  //define('PWCORREO','P&[03IeFu@sw');
?>
