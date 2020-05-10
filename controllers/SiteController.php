<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SubidaForm;
use app\models\Subir2Form;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSubida()
    { 
     $model = new SubidaForm;
    $mensaje = null;      
    if (!Yii::$app->user->isGuest) {      
    
    if ($model->load(Yii::$app->request->post()))    
    {   
        
        if($model->validate())
                {
                    if($model->categoria==2){
                        $contador=0;   
                        $rutafinal = $model->nombre;
                        $directorio = opendir($rutafinal); //ruta actual
                        while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
                        {
                            if ($archivo!="Procesados" && $archivo!="RESTART" && $archivo!="tickets.log" && $archivo!="." && $archivo!=".." && $archivo!="diarios")
                            {       
                            $ruta = $rutafinal.$archivo;// Indicamos la ruta y el nombre del archivo a listar       
                            if(strtoupper(substr($ruta, -11, 1))=="I"){$mensaje = $mensaje . "<strong>$ruta</strong><br>";}
                            else{$mensaje = $mensaje . "$ruta<br>";}
                            $contador++;
                            }
                        } if($contador<="0"){$mensaje = "No hay archivos para subir";}    
                        else{$mensaje = $mensaje . "Total de archivos a subir son: $contador";}
                     }

                    if($model->categoria==3){
                        $contador=0;
                        $log = new Log("tickets", Yii::$app->params['rutalocal']); 
                        $rutafinal = $model->nombre;
                        //$rutafinal=$rutared;
                        $directorio = opendir($rutafinal); //ruta actual
                        while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
                        {
                            if ($archivo!="Procesados" && $archivo!="RESTART" && $archivo!="tickets.log" && $archivo!="." && $archivo!=".." && $archivo!="diarios")//
                            {       
                            $ruta = $rutafinal.$archivo;// Indicamos la ruta y el nombre del archivo a listar       
                            if(strtoupper(substr($ruta, -11, 3))=="999"){
                                unlink($ruta);            
                                $mensaje = $mensaje . "<strong>Archivo $ruta Elimado</strong> <br>";
                                $log->insert("$ruta Eliminado ", false, true, true, true);    
                            }
                            else{$mensaje = $mensaje . "$ruta <br>";}
                            $contador++;
                            }
                        } if($contador<="0"){$mensaje = $mensaje . "No hay archivos para eliminar";}    
                        else{$mensaje = $mensaje . "Total de archivos a subir son: $contador";}

                    } 

                    if($model->categoria==1){

                        date_default_timezone_set('America/Santiago');
                        $log = new Log("tickets", Yii::$app->params['rutalocal']);
                        $diario = new Log(Yii::$app->params['amd'], Yii::$app->params['rutalocal']."diarios/");  
                        $contador=0;
                        $rutafinal = $model->nombre;
                        
                        $conexion_id = ftp_connect(Yii::$app->params['ip_servidor']); // creamos un ID de conexión al servidor
                        
                        @$resultado = ftp_login($conexion_id,Yii::$app->params['user'],Yii::$app->params['pass']); // iniciamos sesion con usuario y contraseña


                        $directorio = opendir($rutafinal); //ruta actual
                        while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
                        {
                            if ($archivo!="Procesados" && $archivo!="RESTART" && $archivo!="tickets.log" && $archivo!="." && $archivo!=".." && $archivo!="diarios")//verificamos si es o no un directorio
                            {       
                            $ruta = $rutafinal.$archivo;// Indicamos la ruta y el nombre del archivo a transmitir        
                            
                        if (strtoupper(substr(PHP_OS, 0, 5)) === 'LINUX') {
                            
                            //chown($ruta, "root"); // Cambio de propietario
                            //chmod($ruta, 0777); // Cambio de permisos

                            $stat = stat($ruta);
                            //print_r(posix_getpwuid($stat['uid']));
                        }
                            

                            if((!$conexion_id) || (!$resultado)){$mensaje = $mensaje . "Falló la conexión con la contraseña ". Yii::$app->params['pass']." intentando con ".Yii::$app->params['pass']."<br>";
                            
                                @$resultado = ftp_login($conexion_id,Yii::$app->params['user'],Yii::$app->params['pass2']); // iniciamos sesion con usuario y contraseña alternativa
                                    
                                ftp_pasv($conexion_id, true);

                                $remoto = Yii::$app->params['rutaserver'].$archivo; // Este es el nombre del archivo que vamos a mandar al servidor

                                $archivo_enviado = ftp_put( $conexion_id, $remoto, $ruta, FTP_ASCII); //subimos el archivo al servidor en modo ASCII

                                if   ($archivo_enviado ) {
                                    $fecha = date('d-M-Y H:i:s');
                                    copy ($ruta,"//PATRICIODONAIRE/o0055ticket/CONTROL/".$archivo );
                                    rename ($ruta,Yii::$app->params['rutalocal']."Procesados/".$archivo );
                                    $mensaje = $mensaje . " $remoto subido y movido a la Carpeta Procesados con fecha: $fecha <br>";
                                    
                                    $log->insert("$remoto subido y movido a la Carpeta Procesados ", false, true, true, true);                
                                    
                                    $contador++;} 
                                else {$mensaje = $mensaje .  "No se pudo enviar el archivo <br>";
                                
                                    $log->insert("$remoto NO subido", false, true, true, true);
                                
                                }
                            
                            }

                            else

                                {

                                //Si se inició sesion, cambiamos a modo pasivo (Las conexiones de datos son iniciadas por el cliente, en lugar de por el servidor. Puede ser necesaria si el cliente está detrás de un firewall)

                                ftp_pasv($conexion_id, true);

                                $remoto = Yii::$app->params['rutaserver'].$archivo; // Este es el nombre del archivo que vamos a mandar al servidor

                                $archivo_enviado = ftp_put( $conexion_id, $remoto, $ruta, FTP_ASCII); //subimos el archivo al servidor en modo binario

                                if   ($archivo_enviado ) {
                                    $fecha = date('d-M-Y H:i:s');
                                    $fecharchivo = date("d-M-Y H:i", filemtime($ruta));
                                    copy ($ruta,"//PATRICIODONAIRE/o0055ticket/CONTROL/".$archivo );
                                    rename ($ruta,Yii::$app->params['rutalocal']."Procesados/".$archivo );
                                    $mensaje = $mensaje . " $remoto subido y movido a la Carpeta Procesados con fecha: $fecha <br>";                 
                                    $log->insert("$remoto subido y movido a la Carpeta Procesados ", false, true, true, true);                
                                    if (substr($archivo,0,1) == "I"){
                                    $diario->insert($archivo."   ".$fecharchivo." <br>", false, false, false, false);}
                                    $contador++;} 
                                else {$mensaje = $mensaje .  "No se pudo enviar el archivo <br>";
                                
                                    $log->insert("$remoto NO subido", false, true, true, true);
                                
                                }
                            
                                }//fin else 

                            }//if procesados 
                            
                            }//end while
                        
                            if($contador<="0"){
                                $log->insert("No hay archivos para subir", false, true, true, true);
                                $log->insert("******************************************************************************", false, true, true, true);
                                $mensaje = $mensaje . "No hay archivos para subir <br>";
                                $mensaje = $mensaje . "Se actualiza el archivo tickets.log en ".Yii::$app->params['rutalocal']."<br>";
                            }else{
                                $log->insert("Subidos en total: $contador", false, true, true, true);
                                $log->insert("******************************************************************************", false, true, true, true);
                                $mensaje = $mensaje . "Total de archivos subidos: $contador <br>";

                                $mensaje = $mensaje . "Se actualiza el archivo tickets.log en ".Yii::$app->params['rutalocal']."<br>";
                        
                            if(@$conexion_id){ftp_close($conexion_id); 
                            /*   header("Status: 301 Moved Permanently"); */
                                    if (headers_sent()) {
                                        // las cabeceras ya se han enviado, no intentar añadir una nueva
                                        ?> <meta http-equiv="Refresh" content="15"> <?php
                                    }
                                    else { 
                                        /* Refrescado de la pagina a los 15 segundos */
                                        // es posible añadir nuevas cabeceras HTTP
                                        header("refresh:15");
                                    } 
                            };//Y por ultimo cerramos la conexión FTP      
                            }

                    } 


                }
                else
                {
                    $model->getErrors();
                }
    }
  
        return $this->render("subida", ["model" => $model, 'mensaje' => $mensaje]);
    }else{
        
        return $this->render('index', ['model' => $model ]);
    }
    }
    
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->render('subida');
            //return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->render('index');
            //return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
class Log
{
public function __construct($filename, $path)
{
$this->path     = ($path) ? $path : "/";
$this->filename = ($filename) ? $filename : "log";
$this->date     = date("d-m-Y H:i:s");
$this->ip       = ($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 0;
}
public function insert($text, $dated, $clear, $backup, $show)
{
if ($dated) {
$date   = "_" . str_replace(" ", "_", $this->date);
$append = null;
}
else {
$date   = "";
$append = ($clear) ? null : FILE_APPEND;
if ($backup) {
$result = (copy($this->path . $this->filename . ".log", $this->path . $this->filename. ".log" )) ? 1 : 0;
$append = ($result) ? $result : FILE_APPEND;
}
};
if ($show){
$log    = $this->date . " [ip] " . $this->ip . " : " . $text . PHP_EOL;}
else {$log    = $text . PHP_EOL;}
$result = (file_put_contents($this->path . $this->filename . $date . ".log", $log, $append)) ? 1 : 0;
 
return $result;
}
}