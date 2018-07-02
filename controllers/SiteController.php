<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\data\SqlDataProvider;
use app\models\LoginForm;
use app\models\User;
use app\models\UserSearch;
use app\models\Waktu;
use app\models\UploadCsv;
use app\models\Daerah;
use app\models\FenomProv;
use app\models\FenomMultiregional;
use app\models\PdrbProv;
use app\models\PdrbProvTahun;
use app\models\PdrbMultiregional;
use app\models\Kbli;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

class SiteController extends Controller
{
    public $layout;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'except' => ['error'],
                'rules' => [
                    [
                        'actions' => [  'knpr-home', 
                                        'knpr-generate',
                                        'knpr-lapres-kabkot',
                                        'knpr-lapres-pulau',
                                        'knpr-manajemen-user',
                                        'knpr-manajemen-waktu',
                                        'knpr-monitoring',
                                        'knpr-peta',
                                        'knpr-regnas-diskrepansi',
                                        'knpr-regnas-diskrepansi-pdrb',
                                        'knpr-regnas-rekap-data',
                                        'knpr-regnas-rekap-fenomena',
                                        'knpr-regnas-rekonsiliasi',
                                        'knpr-regnas-simulasi',
                                        'knpr-upload-data',
                                        'knpr-upload-fenomena',
                                        ],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action)
                        {
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->akses == 1;
                        }
                    ],
                    [
                        'actions' => [  'prov-home', 
                                        'prov-kabkotreg-diskrepansi-pdrb',
                                        'prov-kabkotreg-diskrepansi',
                                        'prov-kabkotreg-rekap-data',
                                        'prov-kabkotreg-rekap-fenomena',
                                        'prov-kabkotreg-rekonsiliasi',
                                        'prov-manajemen-user',
                                        'prov-manajemen-waktu',
                                        'prov-regnas-diskrepansi',
                                        'prov-regnas-rekap-data',
                                        'prov-regnas-rekap-fenomena',
                                        'prov-regnas-rekonsiliasi',
                                        'prov-update-user',
                                        'prov-upload-data',
                                        'prov-upload-fenomena'
                                        ],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action)
                        {
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->akses == 2;
                        }
                    ],
                    [
                        'actions' => [  'multiregional-data',
                                        'multiregional-fenomena',
                                        'multiregional-home',
                                        'multiregional-manajemen-user',
                                        'multiregional-update-user',
                                        'multiregional-upload-data',
                                        'multiregional-upload-fenomena'
                                        ],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action)
                        {
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->akses == 3;
                        }
                    ],
                    [
                        'actions' =>    [  'index', 
                                            'logout',
                                            'error-page',
                                            '_form',
                                            '_search',
                                            'create',
                                            'update',
                                            'view'
                                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' =>    [  'login'
                                        ],
                        'allow' => true,
                        'roles' => ['?'],
                    ],        
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
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
        if (!Yii::$app->user->isGuest){
        if (Yii::$app->user->identity->akses === 1){
            return $this->redirect(['site/knpr-home']);
        } else if (Yii::$app->user->identity->akses === 2) {
            return $this->redirect(['site/prov-home']);
        } else if (Yii::$app->user->identity->akses === 3) {
            return $this->redirect(['site/multiregional-home']);
        } else if (Yii::$app->user->identity->akses === 4) {
            return $this->redirect(['site/kabkot-home']);
        }
     } else {
        return $this->redirect(['site/login'])->send();
     }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {   $this->layout = 'main-login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
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
        return $this->redirect(['site/login'])->send();
    }
    
    //Tampil Halaman Error 
    public function actionErrorPage(array $error){
        return $this->render('error-page', ['error' => $error]);
    }
    
//Controller KNPR
    
   //Beranda KNPR
    public function actionKnprHome()
    {   
        return $this->render('knpr/knpr-home');
    }
    
    //Tampil Monitoring KNPR
    public function actionKnprMonitoring() {
        
        return $this->render('knpr/knpr-monitoring');
    }
    
    //Tampil Upload Data PDB KNPR
    public function actionKnprUploadData() {
        $model = new UploadCsv;
        
        if($model->load(Yii::$app->request->post())){
            $file = UploadedFile::getInstance($model,'file');
            $filename = 'Data.'.$file->extension;
            $upload = $file->saveAs('uploads/'.$filename);
            if($upload){
                define('CSV_PATH','uploads/');
                $csv_file = CSV_PATH . $filename;
                $filecsv = file($csv_file);
                $validasi = null;
                $val_header = $this->validasiFormatDataProv($filecsv[0]);
                if($val_header == 1){
                    $counter = 0;
                    foreach($filecsv as $data){
                        if($counter != 0){
                        $hasil = explode(",",$data);
                        $id_prov = $hasil[0];
                        $id_pdrb = $hasil[1];
                        $tahun = $hasil[3];
                        $triwulan = $hasil[4];
                        $putaran = $hasil[5];
                        $revisi = $hasil[6];
                        $pdrb_k = $hasil[8];

                        $val_sedia = $this->validasiKetersediaanUploadDataProv($id_prov, $tahun, $triwulan, $putaran, $revisi);
                        $val_waktu = $this->validasiUploadWaktuProv($tahun, $triwulan, $putaran, $revisi);
                        $val_user = $this->validasiUploadUser($id_prov, Yii::$app->user->identity->akses);
                        $val_arah = $this->validasiUploadArahProv($tahun, $triwulan, $putaran, $id_prov, $id_pdrb, $pdrb_k, Yii::$app->user->identity->kode_val);

                        if ($val_sedia == 1 && $val_waktu  == 1 && $val_user == 1 && $val_arah == 1 ){
                            $validasi = 1;
                        }
                        else {
                            $error = [];
                            if ($val_sedia == 0){
                                $error[] = "Maaf, data yang anda upload sudah ada di database";
                            }
                            if ($val_waktu == 0){
                                $error[] = "Maaf, data yang anda upload tidak sesuai dengan waktu berjalan yang telah ditentukan";
                            }
                            if ($val_user == 0){
                                $error[] = "Maaf, anda tidak berhak melakukan upload data tersebut";
                            }
                            if ($val_arah == 0){
                                $error[] = "Maaf, data yang anda upload mengalami balik arah dengan data sebelumnya";
                            }
                            $validasi = null;
                            unlink('uploads/'.$filename);
                            return $this->redirect(['site/error-page', 'error' => $error]);
                        }
                        } 
                    $counter++;
                    }
                } else {
                    $error[] = 'Maaf, Format Data Salah';
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['site/error-page', 'error' => $error]);
                } 
                
                if ($validasi == 1){
                    $timestamp = date('Y-m-d h:i:s');
                    $this->processingDataUploadProv($filecsv, $timestamp);
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['site/knpr-home']);
                    }
            }
        }else{
            return $this->render('knpr/knpr-upload-data',['model'=>$model]);
        }
    }   
    
    //Tampil Monitoring KNPR
    public function actionKnprUploadFenomena() {
        $model = new UploadCsv;
        
        if($model->load(Yii::$app->request->post())){
            $file = UploadedFile::getInstance($model,'file');
            $filename = 'Data.'.$file->extension;
            $upload = $file->saveAs('uploads/'.$filename);
            if($upload){
                define('CSV_PATH','uploads/');
                $csv_file = CSV_PATH . $filename;
                $filecsv = file($csv_file);
                $validasi = null;
                $val_header = $this->validasiFormatFenomenaProv($filecsv[0]);
                if ($val_header == 1){
                    $counter = 0;
                    foreach($filecsv as $data){
                        if($counter != 0){
                            $hasil = explode(",",$data);
                            $id_prov = $hasil[0];
                            $id_pdrb = $hasil[1];
                            $tahun = $hasil[2];
                            $triwulan = $hasil[3];
                            $putaran = $hasil[4];
                            $revisi = $hasil[5];
                            $isi_fenom = $hasil[6];
                            $isi_sumber = $hasil[8];
                            $val_sedia = $this->validasiKetersediaanUploadFenomenaProv($id_prov, $id_pdrb, $tahun, $triwulan, $putaran, $revisi, $isi_fenom, $isi_sumber);
                            $val_waktu = $this->validasiUploadWaktuProv($tahun, $triwulan, $putaran, $revisi);
                            $val_user = $this->validasiUploadUser($id_prov, Yii::$app->user->identity->akses);
                            if ($val_sedia == 1 && $val_waktu  == 1 && $val_user == 1){
                                $validasi = 1;
                            }
                            else {
                                $error = [];
                                if ($val_sedia == 0){
                                    $error[] = "Maaf, data yang anda upload sudah ada di database";
                                }
                                if ($val_waktu == 0){
                                    $error[] = "Maaf, data yang anda upload tidak sesuai dengan waktu berjalan yang telah ditentukan";
                                }
                                if ($val_user == 0){
                                    $error[] = "Maaf, anda tidak berhak melakukan upload data tersebut";
                                }
                                $validasi = null;
                                unlink('uploads/'.$filename);
                                return $this->redirect(['site/error-page', 'error' => $error]);
                            }
                        }
                        $counter++;
                    }
                } else {
                    $error[] = 'Maaf, Format Data Salah';
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['site/error-page', 'error' => $error]);
                }
                if ($validasi == 1){
                    $timestamp = date('Y-m-d h:i:s');
                    $this->processingFenomenaUploadProv($filecsv, $timestamp);
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['site/knpr-home']);
                    }
            }
        }else{
            return $this->render('knpr/knpr-upload-fenomena',['model'=>$model]);
        }
    }
    
    //Tampil Diskrepansi RegNas
    public function actionKnprRegnasDiskrepansi() {
        $button = Yii::$app->request->post('Button');
        $item_prov = ArrayHelper::map(Daerah::find()->where(['kode_kab'=>'00'])->orderBy('kode_daerah')->all(), 'kode_daerah', 'nama_provinsi');
        $item_tahun = ArrayHelper::toArray(Waktu::find()->select('tahun')->orderBy('tahun')->distinct()->all());
        $array_tahun = ArrayHelper::getColumn($item_tahun, 'tahun');
        $items_tahun = array_combine($array_tahun, $array_tahun);
        $item_triwulan = ArrayHelper::toArray(Waktu::find()->select('triwulan')->orderBy('triwulan')->distinct()->all()); 
        $array_triwulan = ArrayHelper::getColumn($item_triwulan, 'triwulan');
        $items_triwulan = array_combine($array_triwulan, $array_triwulan);
        if(Yii::$app->request->post('Button')=='show' || Yii::$app->request->post('Button')=='update'
                || Yii::$app->request->post('Button')=='finalisasi' || Yii::$app->request->post('Button')=='definalisasi'){
            $getProv = Yii::$app->request->post('opsi_prov');
            $getTahun = Yii::$app->request->post('opsi_tahun');
            $getTriwulan = Yii::$app->request->post('opsi_triwulan');
            $getRefWaktu = PdrbProv::getReferensiDataTerbaru($getProv, $getTahun, $getTriwulan); 
            $kolom = $this->getKolomRegnasDiskrepansi();
        }
        if(Yii::$app->request->post('Button')=='show'){
            $nama_file = 'diskrepansi '.$getProv.' '.$getTahun.' '.$getTriwulan;
            $dataProvider = $this->getDataDiskreProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            if($dataProvider){
            return $this->render('knpr/knpr-regnas-diskrepansi',[
            'items_0' => $item_prov, 
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'kolom' => $kolom,
            'button' => $button,
            'dataProvider' => $dataProvider,
                'nama'=> $nama_file
                ]);
            } 
            else {
                $button = null;    
            }
        } 
        else if(Yii::$app->request->post('Button')=='update'){
            $this->processingDataUpdateProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            $dataProvider = $this->getDataDiskreProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            $nama_file = 'diskrepansi '.$getProv.' '.$getTahun.' '.$getTriwulan;
            if($dataProvider){
            return $this->render('knpr/knpr-regnas-diskrepansi',[
            'items_0' => $item_prov, 
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => 'show',
            'kolom' => $kolom,
            'dataProvider' => $dataProvider,
               'nama'=> $nama_file
                ]);
            } 
            else {
                $button = null;    
            }
        }
        else if(Yii::$app->request->post('Button')=='finalisasi'){
            $this->processingDataFinalisasiProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            if($getTriwulan == 4){
                if( PdrbProv::find()->where(['id_prov'=>$getProv, 'tahun'=>$getTahun, 'triwulan'=>1, 'status'=>'final'])->exists() &&
                    PdrbProv::find()->where(['id_prov'=>$getProv, 'tahun'=>$getTahun, 'triwulan'=>2, 'status'=>'final'])->exists() &&
                    PdrbProv::find()->where(['id_prov'=>$getProv, 'tahun'=>$getTahun, 'triwulan'=>3, 'status'=>'final'])->exists() &&
                    PdrbProv::find()->where(['id_prov'=>$getProv, 'tahun'=>$getTahun, 'triwulan'=>4, 'status'=>'final'])->exists()
                        )
                    {
                    $this->processingDataProvTahun($getProv, $getTahun);
                }
            }
            $dataProvider = $this->getDataDiskreProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            $nama_file = 'finalisasi '.$getProv.' '.$getTahun.' '.$getTriwulan;
            if($dataProvider){
            return $this->render('knpr/knpr-regnas-diskrepansi',[
            'items_0' => $item_prov,     
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => 'show',
            'kolom' => $kolom,   
            'dataProvider' => $dataProvider,
               'nama'=> $nama_file
                ]);
            } 
            else {
                $button = null;    
            }
        }
        else if(Yii::$app->request->post('Button')=='definalisasi'){
            $this->processingDataDefinalisasiProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            $dataProvider = $this->getDataDiskreProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            $nama_file = 'definalisasi '.$getProv.' '.$getTahun.' '.$getTriwulan;
            if($dataProvider){
            return $this->render('knpr/knpr-regnas-diskrepansi',[
            'items_0' => $item_prov, 
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => 'show',
            'kolom' => $kolom,  
            'dataProvider' => $dataProvider,
               'nama'=> $nama_file
                ]);
            } 
            else {
                $button = null;    
            }
        }
        return $this->render('knpr/knpr-regnas-diskrepansi',[
            'items_0' => $item_prov, 
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => $button,
                ]);
        
    }
    
    //Tampil Regnas Diskrepansi berdasar kbli
    public function actionKnprRegnasDiskrepansiPdrb() {
        $button = Yii::$app->request->post('Button');
        $item_pdrb = ArrayHelper::map(KBLI::find()->orderBy('kode_pdrb')->all(), 'kode_pdrb', 'ket_pdrb');
        $item_tahun = ArrayHelper::toArray(Waktu::find()->select('tahun')->orderBy('tahun')->distinct()->all());
        $array_tahun = ArrayHelper::getColumn($item_tahun, 'tahun');
        $items_tahun = array_combine($array_tahun, $array_tahun);
        $item_triwulan = ArrayHelper::toArray(Waktu::find()->select('triwulan')->orderBy('triwulan')->distinct()->all()); 
        $array_triwulan = ArrayHelper::getColumn($item_triwulan, 'triwulan');
        $items_triwulan = array_combine($array_triwulan, $array_triwulan);
        if(Yii::$app->request->post('Button')=='show'){
            $getPdrb = Yii::$app->request->post('opsi_pdrb');
            $getTahun = Yii::$app->request->post('opsi_tahun');
            $getTriwulan = Yii::$app->request->post('opsi_triwulan');
            $nama_file = 'diskrepansi '.$getPdrb.' '.$getTahun.' '.$getTriwulan;        
            $kolom = $this->getKolomRegnasDiskrepansi();
            if(PdrbProv::find()->where(['id_pdrb'=>$getPdrb, 'tahun'=>$getTahun, 'triwulan'=>$getTriwulan])->exists()){
                $dataProvider = $this->getDataDiskrePdrbProv($getPdrb, $getTahun, $getTriwulan);
                return $this->render('knpr/knpr-regnas-diskrepansi-pdrb',[
                    'items_0' => $item_pdrb, 
                    'items_1' => $items_tahun, 
                    'items_2' => $items_triwulan,
                    'button' => $button,
                    'kolom' => $kolom,
                    'dataProvider' => $dataProvider,
                    'nama' => $nama_file,
                        ]);      
                }    
            
            else {
                $button = null;    
            }
            
        } 
        return $this->render('knpr/knpr-regnas-diskrepansi-pdrb',['items_0' => $item_pdrb, 
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => $button]);
    }
    
    //Tampil Rekonsiliasi RegNas
    public function actionKnprRegnasRekonsiliasi() {
       $button = Yii::$app->request->post('Button');
       if(Yii::$app->request->post('adj_adhb') || Yii::$app->request->post('adj_adhk')){
        $adjust = [Yii::$app->request->post('adj_adhb'), Yii::$app->request->post('adj_adhk')];
        } else {
            $adjust = [0,0];
        }
        $item_prov = ArrayHelper::map(Daerah::find()->where(['kode_kab'=>'00'])->orderBy('kode_daerah')->all(), 'kode_daerah', 'nama_provinsi');
        $item_pdrb = ArrayHelper::map(KBLI::find()->orderBy('kode_pdrb')->all(), 'kode_pdrb', 'ket_pdrb');
        $item_tahun = ArrayHelper::toArray(Waktu::find()->select('tahun')->orderBy('tahun')->distinct()->all());
        $array_tahun = ArrayHelper::getColumn($item_tahun, 'tahun');
        $items_tahun = array_combine($array_tahun, $array_tahun);
        $item_triwulan = ArrayHelper::toArray(Waktu::find()->select('triwulan')->orderBy('triwulan')->distinct()->all()); 
        $array_triwulan = ArrayHelper::getColumn($item_triwulan, 'triwulan');
        $items_triwulan = array_combine($array_triwulan, $array_triwulan);
        if(Yii::$app->request->post('Button')=='adjust'){
            $getProv = Yii::$app->request->post('opsi_prov');
            $getPdrb = Yii::$app->request->post('opsi_pdrb');
            $getTahun = Yii::$app->request->post('opsi_tahun');
            $getTriwulan = Yii::$app->request->post('opsi_triwulan');
            
            $nama_file = 'adjusment '.$getProv.' '.$getPdrb.' '.$getTahun.' '.$getTriwulan;
            if($getTriwulan == 1){
                $tahun_0 = $getTahun - 1;
                $triwulan_0 = 4;
            }
            else {
                $tahun_0 = $getTahun;
                $triwulan_0 = $getTriwulan-1;
            }
            if(PdrbProv::find()->where(['id_prov'=>$getProv, 'id_pdrb'=>$getPdrb, 'tahun'=>$getTahun, 'triwulan'=>$getTriwulan])->exists()
                    && PdrbProv::find()->where(['id_prov'=>$getProv, 'id_pdrb'=>$getPdrb, 'tahun'=>$tahun_0, 'triwulan'=>$triwulan_0])->exists()
                    && PdrbProv::find()->where(['id_prov'=>'0100', 'id_pdrb'=>$getPdrb, 'tahun'=>$getTahun, 'triwulan'=>$getTriwulan])->exists()){
                $total_adhb_selain = PdrbProv::getTotalADHBProvSelain($getProv, $getPdrb, $getTahun, $getTriwulan);
                $total_adhk_selain = PdrbProv::getTotalADHKProvSelain($getProv, $getPdrb, $getTahun, $getTriwulan);
                $total_adhb = PdrbProv::getTotalADHBProv($getPdrb, $getTahun, $getTriwulan);
                $total_adhk = PdrbProv::getTotalADHKProv($getPdrb, $getTahun, $getTriwulan);
                $dataProvider = $this->getDataAdjustPdrbProv($getProv, $getPdrb, $getTahun, $getTriwulan, $tahun_0, $triwulan_0);
                
                $kolom = $this->getKolomRegnasRekonsiliasi($adjust, $total_adhb_selain, $total_adhk_selain, $total_adhb, $total_adhk);
                return $this->render('knpr/knpr-regnas-rekonsiliasi',[
                    'items_0' => $item_prov, 
                    'items_1' => $item_pdrb, 
                    'items_2' => $items_tahun, 
                    'items_3' => $items_triwulan,
                    'button' => $button,
                    'kolom' => $kolom,
                    'dataProvider' => $dataProvider,
                    'nama' => $nama_file,
                    'adjust' => $adjust
                        ]);      
                    }
            else {
                $button = null;  
            }
            
        } 
        return $this->render('knpr/knpr-regnas-rekonsiliasi',[
            'items_0' => $item_prov, 
            'items_1' => $item_pdrb, 
            'items_2' => $items_tahun, 
            'items_3' => $items_triwulan,
            'button' => $button,
            'adjust' => $adjust
                ]);

    }
    
    //Tampil Simulasi RegNas
    public function actionKnprRegnasSimulasi() {
        return $this->render('knpr/knpr-regnas-simulasi');
    }
    
    //Tampil Rekap Data Regnas
    public function actionKnprRegnasRekapData() {
        return $this->render('knpr/knpr-regnas-rekap-data');
    }
    
    //Tampil Rekap Fenomena Regnas
    public function actionKnprRegnasRekapFenomena() {
        return $this->render('knpr/knpr-regnas-rekap-fenomena');
    }
    
    //Tampil Lapres Pulau KNPR
    public function actionKnprLapresPulau() {
        return $this->render('knpr/knpr-lapres-pulau');
    }
    
    //Tampil Manajemen Waktu KNPR
    public function actionKnprLapresKabkot() {
        return $this->render('knpr/knpr-lapres-kabkot');
    }
    
    //Tampil Generate KNPR
    public function actionKnprGenerate() {
        return $this->render('knpr/knpr-generate');
    }
    
    //Tampil Peta KNPR
    public function actionKnprPeta() {
        return $this->render('knpr/knpr-peta');
    }
    

    public function actionKnprManajemenUser()
    {   
        $searchModel = new UserSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
        return $this->render('knpr/knpr-manajemen-user', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('knpr/view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $user = User::findOne(['username' => $model->username ]) ;
            return $this->redirect(['site/view','id' => $user->id]);
        } else {
            return $this->render('knpr/create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->save();
            return $this->redirect(['site/view', 'id' => $model->id]);
        } else {
            return $this->render('knpr/update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['site/knpr-manajemen-user']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    //Tampil Manajemen Waktu KNPR
    public function actionKnprManajemenWaktu() {
        $model = new Waktu;
        if (Waktu::find()->where(['status'=>'aktif'])->exists()){
            $model_0 = Waktu::find()->where(['status'=>'aktif'])->one();
        } 
        else {
            $model_0 = null;
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $waktu = $model->tahun.'/'.$model->triwulan.'/'.$model->putaran;
            if (!$model_update = Waktu::find()->where(['id_waktu' => $waktu])->exists()){
               $model->save(); 
            } else {
                $model_update = Waktu::find()->where(['id_waktu' => $waktu])->one();
                Waktu::gantiStatus();
                $model_update->status ='aktif';
                $model_update->save($runValidation=false);
            }
            return $this->redirect(['site/knpr-home']);
        } else {
            return $this->render('knpr/knpr-manajemen-waktu', [
                'model'  => $model,
                'model_0' => $model_0
            ]);
        }    
    }
    
//Controller Provinsi

    //Beranda Prov
    public function actionProvHome()
    {
        return $this->render('prov/prov-home');
    }
    
    //Tampil Manajemen User Prov
    public function actionProvManajemenUser() {
        $model = $this->findModel(Yii::$app->user->identity->id);
        return $this->render('prov/prov-manajemen-user', [
        'model' => $model,
        ]);
        }

    //Update profil user provinsi
    public function actionProvUpdateUser($id){
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['site/prov-home', 'id' => $model->id]);
        } else {
            return $this->render('prov/prov-update-user', [
                'model' => $model,
            ]);
        }
    }
        
    //Tampil Diskrepansi RegNas
    public function actionProvRegnasDiskrepansi() {
    $button = Yii::$app->request->post('Button');
        $item_tahun = ArrayHelper::toArray(Waktu::find()->select('tahun')->orderBy('tahun')->distinct()->all());
        $array_tahun = ArrayHelper::getColumn($item_tahun, 'tahun');
        $items_tahun = array_combine($array_tahun, $array_tahun);
        $item_triwulan = ArrayHelper::toArray(Waktu::find()->select('triwulan')->orderBy('triwulan')->distinct()->all()); 
        $array_triwulan = ArrayHelper::getColumn($item_triwulan, 'triwulan');
        $items_triwulan = array_combine($array_triwulan, $array_triwulan);
        if(Yii::$app->request->post('Button')=='show' || Yii::$app->request->post('Button')=='update' || 
                Yii::$app->request->post('Button')=='finalisasi' || Yii::$app->request->post('Button')=='definalisasi'){
            $getProv = Yii::$app->user->identity->kode_daerah;
            $getTahun = Yii::$app->request->post('opsi_tahun');
            $getTriwulan = Yii::$app->request->post('opsi_triwulan');
            $getRefWaktu = PdrbProv::getReferensiDataTerbaru($getProv, $getTahun, $getTriwulan); 
            $kolom = $this->getKolomRegnasDiskrepansi();
        }
        if(Yii::$app->request->post('Button')=='show'){
            $nama_file = 'diskrepansi '.$getProv.' '.$getTahun.' '.$getTriwulan;
            $dataProvider = $this->getDataDiskreProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            if($dataProvider){
            return $this->render('prov/prov-regnas-diskrepansi',[
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => $button,
            'kolom' => $kolom,
            'dataProvider' => $dataProvider,
                'nama'=> $nama_file
                ]);
            } 
            else {
                $button = null;    
            }
        } 
        else if(Yii::$app->request->post('Button')=='update'){
            $this->processingDataUpdateProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            $dataProvider = $this->getDataDiskreProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            $nama_file = 'diskrepansi '.$getProv.' '.$getTahun.' '.$getTriwulan;
            if($dataProvider){
            return $this->render('prov/prov-regnas-diskrepansi',[
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => 'show',
            'kolom' => $kolom,
            'dataProvider' => $dataProvider,
               'nama'=> $nama_file
                ]);
            } 
            else {
                $button = null;    
            }
        }
        else if(Yii::$app->request->post('Button')=='finalisasi'){
            $this->processingDataFinalisasiProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            if($getTriwulan == 4){
                if( PdrbProv::find()->where(['id_prov'=>$getProv, 'tahun'=>$getTahun, 'triwulan'=>1, 'status'=>'final'])->exists() &&
                    PdrbProv::find()->where(['id_prov'=>$getProv, 'tahun'=>$getTahun, 'triwulan'=>2, 'status'=>'final'])->exists() &&
                    PdrbProv::find()->where(['id_prov'=>$getProv, 'tahun'=>$getTahun, 'triwulan'=>3, 'status'=>'final'])->exists() &&
                    PdrbProv::find()->where(['id_prov'=>$getProv, 'tahun'=>$getTahun, 'triwulan'=>4, 'status'=>'final'])->exists()
                        )
                    {
                    $this->processingDataProvTahun($getProv, $getTahun);
                }
            }
            $dataProvider = $this->getDataDiskreProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            $nama_file = 'finalisasi '.$getProv.' '.$getTahun.' '.$getTriwulan;
            if($dataProvider){
            return $this->render('prov/prov-regnas-diskrepansi',[
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => 'show',
            'kolom' => $kolom,
            'dataProvider' => $dataProvider,
               'nama'=> $nama_file
                ]);
            } 
            else {
                $button = null;    
            }
        }
        else if(Yii::$app->request->post('Button')=='definalisasi'){
            $this->processingDataDefinalisasiProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            $dataProvider = $this->getDataDiskreProv($getProv, $getTahun, $getTriwulan, $getRefWaktu);
            $nama_file = 'definalisasi '.$getProv.' '.$getTahun.' '.$getTriwulan;
            if($dataProvider){
            return $this->render('prov/prov-regnas-diskrepansi',[
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => 'show',
            'kolom' => $kolom,
            'dataProvider' => $dataProvider,
               'nama'=> $nama_file
                ]);
            } 
            else {
                $button = null;    
            }
        }
        return $this->render('prov/prov-regnas-diskrepansi',[
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => $button,
                ]);
         
    }
    
    public function actionProvRegnasRekonsiliasi() {
        $button = Yii::$app->request->post('Button');
        if(Yii::$app->request->post('adj_adhb') || Yii::$app->request->post('adj_adhk')){
        $adjust = [Yii::$app->request->post('adj_adhb'), Yii::$app->request->post('adj_adhk')];
        } else {
            $adjust = [0,0];
        }
        $item_pdrb = ArrayHelper::map(KBLI::find()->orderBy('kode_pdrb')->all(), 'kode_pdrb', 'ket_pdrb');
        $item_tahun = ArrayHelper::toArray(Waktu::find()->select('tahun')->orderBy('tahun')->distinct()->all());
        $array_tahun = ArrayHelper::getColumn($item_tahun, 'tahun');
        $items_tahun = array_combine($array_tahun, $array_tahun);
        $item_triwulan = ArrayHelper::toArray(Waktu::find()->select('triwulan')->orderBy('triwulan')->distinct()->all()); 
        $array_triwulan = ArrayHelper::getColumn($item_triwulan, 'triwulan');
        $items_triwulan = array_combine($array_triwulan, $array_triwulan);
        if(Yii::$app->request->post('Button')=='adjust'){
            $getProv = Yii::$app->user->identity->kode_daerah;
            $getPdrb = Yii::$app->request->post('opsi_pdrb');
            $getTahun = Yii::$app->request->post('opsi_tahun');
            $getTriwulan = Yii::$app->request->post('opsi_triwulan');
            
            $nama_file = 'adjusment '.$getProv.' '.$getPdrb.' '.$getTahun.' '.$getTriwulan;
            if($getTriwulan == 1){
                $tahun_0 = $getTahun - 1;
                $triwulan_0 = 4;
            }
            else {
                $tahun_0 = $getTahun;
                $triwulan_0 = $getTriwulan-1;
            }
            if(PdrbProv::find()->where(['id_prov'=>$getProv, 'id_pdrb'=>$getPdrb, 'tahun'=>$getTahun, 'triwulan'=>$getTriwulan])->exists()
                    && PdrbProv::find()->where(['id_prov'=>$getProv, 'id_pdrb'=>$getPdrb, 'tahun'=>$tahun_0, 'triwulan'=>$triwulan_0])->exists()
                    && PdrbProv::find()->where(['id_prov'=>'0100', 'id_pdrb'=>$getPdrb, 'tahun'=>$getTahun, 'triwulan'=>$getTriwulan])->exists()){
                $total_adhb_selain = PdrbProv::getTotalADHBProvSelain($getProv, $getPdrb, $getTahun, $getTriwulan);
                $total_adhk_selain = PdrbProv::getTotalADHKProvSelain($getProv, $getPdrb, $getTahun, $getTriwulan);
                $total_adhb = PdrbProv::getTotalADHBProv($getPdrb, $getTahun, $getTriwulan);
                $total_adhk = PdrbProv::getTotalADHKProv($getPdrb, $getTahun, $getTriwulan);
                $dataProvider = $this->getDataAdjustPdrbProv($getProv, $getPdrb, $getTahun, $getTriwulan, $tahun_0, $triwulan_0);
                
                $kolom = $this->getKolomRegnasRekonsiliasi($adjust, $total_adhb_selain, $total_adhk_selain, $total_adhb, $total_adhk);
                return $this->render('prov/prov-regnas-rekonsiliasi',[
                    'items_1' => $item_pdrb, 
                    'items_2' => $items_tahun, 
                    'items_3' => $items_triwulan,
                    'button' => $button,
                    'kolom' => $kolom,
                    'dataProvider' => $dataProvider,
                    'nama' => $nama_file,
                    'adjust' => $adjust
                        ]);      
                    }
            else {
                $button = null;  
            }
            
        } 
        return $this->render('prov/prov-regnas-rekonsiliasi',[
            'items_1' => $item_pdrb, 
            'items_2' => $items_tahun, 
            'items_3' => $items_triwulan,
            'button' => $button,
            'adjust' => $adjust
                ]);

    }
    
    
    //Tampil Rekap Data Regnas
    public function actionProvRegnasRekapData() {
        return $this->render('prov/prov-regnas-rekap-data');
    }
    
    //Tampil Rekap Fenomena Regnas
    public function actionProvRegnasRekapFenomena() {
        return $this->render('prov/prov-regnas-rekap-fenomena');
    }
    
    //Tampil Upload Data Prov
    public function actionProvUploadData() {
        $model = new UploadCsv;
        
        if($model->load(Yii::$app->request->post())){
            $file = UploadedFile::getInstance($model,'file');
            $filename = 'Data.'.$file->extension;
            $upload = $file->saveAs('uploads/'.$filename);
            if($upload){
                define('CSV_PATH','uploads/');
                $csv_file = CSV_PATH . $filename;
                $filecsv = file($csv_file);
                $validasi = null;
                $val_header = $this->validasiFormatDataProv($filecsv[0]);
                if ($val_header == 1){
                    $counter = 0;
                    foreach($filecsv as $data){
                        if($counter != 0){
                            $hasil = explode(",",$data);
                            $id_prov = $hasil[0];
                            $id_pdrb = $hasil[1];
                            $tahun = $hasil[3];
                            $triwulan = $hasil[4];
                            $putaran = $hasil[5];
                            $revisi = $hasil[6];
                            $pdrb_k = $hasil[8];
                            $val_sedia = $this->validasiKetersediaanUploadDataProv($id_prov, $tahun, $triwulan, $putaran, $revisi);
                            $val_waktu = $this->validasiUploadWaktuProv($tahun, $triwulan, $putaran, $revisi);
                            $val_user = $this->validasiUploadUser($id_prov, Yii::$app->user->identity->akses);
                            $val_arah = $this->validasiUploadArahProv($tahun, $triwulan, $putaran, $id_prov, $id_pdrb, $pdrb_k, Yii::$app->user->identity->kode_val);
                            if ($val_sedia == 1 && $val_waktu  == 1 && $val_user == 1 && $val_arah == 1 ){
                                $validasi = 1;
                            }
                            else {
                                $error = [];
                                if ($val_sedia == 0){
                                    $error[] = "Maaf, data yang anda upload sudah ada di database";
                                }
                                if ($val_waktu == 0){
                                    $error[] = "Maaf, data yang anda upload tidak sesuai dengan waktu berjalan yang telah ditentukan";
                                }
                                if ($val_user == 0){
                                    $error[] = "Maaf, anda tidak berhak melakukan upload data tersebut";
                                }
                                if ($val_arah == 0){
                                    $error[] = "Maaf, data yang anda upload mengalami balik arah dengan data sebelumnya";
                                }
                                $validasi = null;
                                unlink('uploads/'.$filename);
                                return $this->redirect(['site/error-page', 'error' => $error]);
                            }
                        }
                        $counter++;
                    }
                } else {
                    $error[] = 'Maaf, Format Data Salah';
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['site/error-page', 'error' => $error]);
                }
                if ($validasi == 1){
                    $timestamp = date('Y-m-d h:i:s');
                    $this->processingDataUploadProv($filecsv, $timestamp);
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['site/prov-home']);
                    }
            }
        }else{
            return $this->render('prov/prov-upload-data',['model'=>$model]);
        }
    }
    
    
    //Tampil Upload Fenomena Prov
    public function actionProvUploadFenomena() {
        $model = new UploadCsv;
        
        if($model->load(Yii::$app->request->post())){
            $file = UploadedFile::getInstance($model,'file');
            $filename = 'Data.'.$file->extension;
            $upload = $file->saveAs('uploads/'.$filename);
            if($upload){
                define('CSV_PATH','uploads/');
                $csv_file = CSV_PATH . $filename;
                $filecsv = file($csv_file);
                $validasi = null;
                $val_header = $this->validasiFormatFenomenaProv($filecsv[0]);
                if ($val_header == 1){
                    $counter = 0;
                    foreach($filecsv as $data){
                        if($counter != 0){
                            $hasil = explode(",",$data);
                            $id_prov = $hasil[0];
                            $id_pdrb = $hasil[1];
                            $tahun = $hasil[2];
                            $triwulan = $hasil[3];
                            $putaran = $hasil[4];
                            $revisi = $hasil[5];
                            $isi_fenom = $hasil[6];
                            $isi_sumber = $hasil[8];
                            $val_sedia = $this->validasiKetersediaanUploadFenomenaProv($id_prov, $id_pdrb, $tahun, $triwulan, $putaran, $revisi, $isi_fenom, $isi_sumber);
                            $val_waktu = $this->validasiUploadWaktuProv($tahun, $triwulan, $putaran, $revisi);
                            $val_user = $this->validasiUploadUser($id_prov, Yii::$app->user->identity->akses);
                            if ($val_sedia == 1 && $val_waktu  == 1 && $val_user == 1){
                                $validasi = 1;
                            }
                            else {
                                $error = [];
                                if ($val_sedia == 0){
                                    $error[] = "Maaf, data yang anda upload sudah ada di database";
                                }
                                if ($val_waktu == 0){
                                    $error[] = "Maaf, data yang anda upload tidak sesuai dengan waktu berjalan yang telah ditentukan";
                                }
                                if ($val_user == 0){
                                    $error[] = "Maaf, anda tidak berhak melakukan upload data tersebut";
                                }
                                $validasi = null;
                                unlink('uploads/'.$filename);
                                return $this->redirect(['site/error-page', 'error' => $error]);
                            }
                        }
                        $counter++;
                    }
                } else {
                    $error[] = 'Maaf, Format Data Salah';
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['site/error-page', 'error' => $error]);
                }
                if ($validasi == 1){
                    $timestamp = date('Y-m-d h:i:s');
                    $this->processingFenomenaUploadProv($filecsv, $timestamp);
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['prov/prov-home']);
                    }
            }
        }else{
            return $this->render('prov/prov-upload-fenomena',['model'=>$model]);
        }
    }
    

    
//Controller Multiregional
 
    //Tampil Home Multiregional
    public function actionMultiregionalHome() {
        return $this->render('multiregional/multiregional-home');
    }
    
    //Tampil Data Multiregional
    public function actionMultiregionalData() {
        $button = Yii::$app->request->post('Button');
        $item_pdrb = ArrayHelper::map(KBLI::find()->orderBy('kode_pdrb')->all(), 'kode_pdrb', 'ket_pdrb');
        $item_tahun = ArrayHelper::toArray(Waktu::find()->select('tahun')->orderBy('tahun')->distinct()->all());
        $array_tahun = ArrayHelper::getColumn($item_tahun, 'tahun');
        $items_tahun = array_combine($array_tahun, $array_tahun);
        $item_triwulan = ArrayHelper::toArray(Waktu::find()->select('triwulan')->orderBy('triwulan')->distinct()->all()); 
        $array_triwulan = ArrayHelper::getColumn($item_triwulan, 'triwulan');
        $items_triwulan = array_combine($array_triwulan, $array_triwulan);
        if(Yii::$app->request->post('Button')=='show'){
            $getPdrb = Yii::$app->request->post('opsi_pdrb');
            $getTahun = Yii::$app->request->post('opsi_tahun');
            $getTriwulan = Yii::$app->request->post('opsi_triwulan');
            $nama_file = 'data multiregional '.$getPdrb.' '.$getTahun.' '.$getTriwulan;
            if(PdrbMultiregional::find()->where(['id_pdrb'=>$getPdrb, 'tahun'=>$getTahun, 'triwulan'=>$getTriwulan])->exists()){
                $dataProvider = $this->getDataMultiregional($getPdrb,$getTahun,$getTriwulan);
                
                return $this->render('multiregional/multiregional-data',[
                    'items_0' => $item_pdrb, 
                    'items_1' => $items_tahun, 
                    'items_2' => $items_triwulan,
                    'button' => $button,
                    'dataProvider' => $dataProvider,
                    'nama' => $nama_file,
                        ]);      
                }    
            
            else {
                $button = null;    
            }
            
        } 
        return $this->render('multiregional/multiregional-data',[
            'items_0' => $item_pdrb, 
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => $button]);
    }
    
    //Tampil Feomena Multiregional
    public function actionMultiregionalFenomena() {
        $button = Yii::$app->request->post('Button');
        $item_pdrb = ArrayHelper::map(KBLI::find()->orderBy('kode_pdrb')->all(), 'kode_pdrb', 'ket_pdrb');
        $item_tahun = ArrayHelper::toArray(Waktu::find()->select('tahun')->orderBy('tahun')->distinct()->all());
        $array_tahun = ArrayHelper::getColumn($item_tahun, 'tahun');
        $items_tahun = array_combine($array_tahun, $array_tahun);
        $item_triwulan = ArrayHelper::toArray(Waktu::find()->select('triwulan')->orderBy('triwulan')->distinct()->all()); 
        $array_triwulan = ArrayHelper::getColumn($item_triwulan, 'triwulan');
        $items_triwulan = array_combine($array_triwulan, $array_triwulan);
        if(Yii::$app->request->post('Button')=='show'){
            $getPdrb = Yii::$app->request->post('opsi_pdrb');
            $getTahun = Yii::$app->request->post('opsi_tahun');
            $getTriwulan = Yii::$app->request->post('opsi_triwulan');
            $nama_file = 'fenomena multiregional '.$getPdrb.' '.$getTahun.' '.$getTriwulan;
            if(FenomMultiregional::find()->where(['id_pdrb'=>$getPdrb, 'tahun'=>$getTahun, 'triwulan'=>$getTriwulan])->exists()){
                $dataProvider = $this->getFenomenaMultiregional($getPdrb,$getTahun,$getTriwulan);
                
                return $this->render('multiregional/multiregional-fenomena',[
                    'items_0' => $item_pdrb, 
                    'items_1' => $items_tahun, 
                    'items_2' => $items_triwulan,
                    'button' => $button,
                    'dataProvider' => $dataProvider,
                    'nama' => $nama_file,
                        ]);      
                }    
            
            else {
                $button = null;    
            }
            
        } 
        return $this->render('multiregional/multiregional-fenomena',[
            'items_0' => $item_pdrb, 
            'items_1' => $items_tahun, 
            'items_2' => $items_triwulan,
            'button' => $button]);
    }
    
    //Tampil Upload Data Multiregional
    public function actionMultiregionalUploadData() {
        $model = new UploadCsv;
        
        if($model->load(Yii::$app->request->post())){
            $file = UploadedFile::getInstance($model,'file');
            $filename = 'Data.'.$file->extension;
            $upload = $file->saveAs('uploads/'.$filename);
            if($upload){
                define('CSV_PATH','uploads/');
                $csv_file = CSV_PATH . $filename;
                $filecsv = file($csv_file);
                $validasi = null;
                $val_header = $this->validasiFormatDataProv($filecsv[0]);
                if($val_header == 1){
                    $counter = 0;
                    foreach($filecsv as $data){
                        if($counter != 0){
                        $hasil = explode(",",$data);
                        $id_prov = $hasil[0];
                        $id_pdrb = $hasil[1];
                        $tahun = $hasil[3];
                        $triwulan = $hasil[4];
                        $putaran = $hasil[5];
                        $revisi = $hasil[6];

                        $val_sedia = $this->validasiKetersediaanUploadDataMultiregional($id_prov, $tahun, $triwulan, $putaran, $revisi);
                        $val_waktu = $this->validasiUploadWaktuProv($tahun, $triwulan, $putaran, $revisi);
                        $val_user = $this->validasiUploadUser($id_prov, Yii::$app->user->identity->akses);

                        if ($val_sedia == 1 && $val_waktu  == 1 && $val_user == 1){
                            $validasi = 1;
                        }
                        else {
                            $error = [];
                            if ($val_sedia == 0){
                                $error[] = "Maaf, data yang anda upload sudah ada di database";
                            }
                            if ($val_waktu == 0){
                                $error[] = "Maaf, data yang anda upload tidak sesuai dengan waktu berjalan yang telah ditentukan";
                            }
                            if ($val_user == 0){
                                $error[] = "Maaf, anda tidak berhak melakukan upload data tersebut";
                            }
                            $validasi = null;
                            unlink('uploads/'.$filename);
                            return $this->redirect(['site/error-page', 'error' => $error]);
                        }
                        } 
                    $counter++;
                    }
                } else {
                    $error[] = 'Maaf, Format Data Salah';
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['site/error-page', 'error' => $error]);
                } 
                
                if ($validasi == 1){
                    $timestamp = date('Y-m-d h:i:s');
                    $this->processingDataUploadMultiregional($filecsv, $timestamp);
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['site/multiregional-home']);
                    }
            }
        }else{
            return $this->render('multiregional/multiregional-upload-data',['model'=>$model]);
        }
    }
    
    //Tampil Upload Fenomena Multiregional
    public function actionMultiregionalUploadFenomena() {
        $model = new UploadCsv;
        
        if($model->load(Yii::$app->request->post())){
            $file = UploadedFile::getInstance($model,'file');
            $filename = 'Data.'.$file->extension;
            $upload = $file->saveAs('uploads/'.$filename);
            if($upload){
                define('CSV_PATH','uploads/');
                $csv_file = CSV_PATH . $filename;
                $filecsv = file($csv_file);
                $validasi = null;
                $val_header = $this->validasiFormatFenomenaProv($filecsv[0]);
                if ($val_header == 1){
                    $counter = 0;
                    foreach($filecsv as $data){
                        if($counter != 0){
                            $hasil = explode(",",$data);
                            $id_prov = $hasil[0];
                            $id_pdrb = $hasil[1];
                            $tahun = $hasil[2];
                            $triwulan = $hasil[3];
                            $putaran = $hasil[4];
                            $revisi = $hasil[5];
                            $isi_fenom = $hasil[6];
                            $isi_sumber = $hasil[8];
                            $val_sedia = $this->validasiKetersediaanUploadFenomenaMultiregional($id_prov, $id_pdrb, $tahun, $triwulan, $putaran, $revisi, $isi_fenom, $isi_sumber);
                            $val_waktu = $this->validasiUploadWaktuProv($tahun, $triwulan, $putaran, $revisi);
                            $val_user = $this->validasiUploadUser($id_prov, Yii::$app->user->identity->akses);
                            if ($val_sedia == 1 && $val_waktu  == 1 && $val_user == 1){
                                $validasi = 1;
                            }
                            else {
                                $error = [];
                                if ($val_sedia == 0){
                                    $error[] = "Maaf, data yang anda upload sudah ada di database";
                                }
                                if ($val_waktu == 0){
                                    $error[] = "Maaf, data yang anda upload tidak sesuai dengan waktu berjalan yang telah ditentukan";
                                }
                                if ($val_user == 0){
                                    $error[] = "Maaf, anda tidak berhak melakukan upload data tersebut";
                                }
                                $validasi = null;
                                unlink('uploads/'.$filename);
                                return $this->redirect(['site/error-page', 'error' => $error]);
                            }
                        }
                        $counter++;
                    }
                } else {
                    $error[] = 'Maaf, Format Data Salah';
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['site/error-page', 'error' => $error]);
                }
                if ($validasi == 1){
                    $timestamp = date('Y-m-d h:i:s');
                    $this->processingFenomenaUploadMultiregional($filecsv, $timestamp);
                    $validasi = null;
                    unlink('uploads/'.$filename);
                    return $this->redirect(['multiregional/multiregional-home']);
                    }
            }
        }else{
            return $this->render('multiregional/multiregional-upload-fenomena',['model'=>$model]);
        }
    }
    
    //Tampil Manajemen User Multiregional
    public function actionMultiregionalManajemenUser() {
        $model = $this->findModel(Yii::$app->user->identity->id);
        return $this->render('multiregional/multiregional-manajemen-user', [
        'model' => $model,
        ]);
    }
    
    //Update profil user
    public function actionMultiregionalUpdateUser($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['site/multiregional-home', 'id' => $model->id]);
        } else {
            return $this->render('multiregional/multiregional-update-user', [
                'model' => $model,
            ]);
        }
    }
    
    //Seluruh Fungsi untuk menghitung diletakkan di bawah sini
    
    //SELURUH RULE VALIDASI
    
//Validasi Header File
    public function validasiFormatFenomenaProv($header){
        
        $header = explode(",", $header);
        $col = count($header);
        if ( trim($header[0]) == "IdProv" 
            && trim($header[1]) == "IdPdrb" 
            && trim($header[2]) == "Tahun" 
            && trim($header[3]) == "Triwulan" 
            && trim($header[4]) == "Putaran" 
            && trim($header[5]) == "Revisi" 
            && trim($header[6]) == "Fenom" 
            && trim($header[7]) == "Tipe" 
            && trim($header[8]) == "Sumber" 
            && trim($header[9]) == "Indikasi" 
            && $col == 10  )
            {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function validasiFormatDataProv($header){
        
        $header = explode(",", $header);
        $col = count($header);
        if (    trim($header[0]) === "IdProv"
            && trim($header[1]) === "IdPdrb" 
            && trim($header[2]) === "TahunDasar" 
            && trim($header[3]) === "Tahun"
            && trim($header[4]) === "Triwulan" 
            && trim($header[5]) === "Putaran" 
            && trim($header[6]) === "Revisi" 
            && trim($header[7]) === "ADHB" 
            && trim($header[8]) === "ADHK" 
              && $col == 9  )
            {
            return 1;
        } else {
            return 0;
        }
    }

//Validasi Ketersediaan Data  PDRB dan Fenomena
    
    public function validasiKetersediaanUploadFenomenaProv($id_prov, $id_pdrb, $tahun, $triwulan, $putaran, $revisi, $isi_fenom, $isi_sumber){
        if (FenomProv::find()->where(['id_prov'=>$id_prov, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan, 'revisi'=>$revisi, 
            'isi_fenom'=>$isi_fenom, 'isi_sumber'=>$isi_sumber])->exists()){
            return 0;
        } 
        else {
            return 1;
        }
    }
     
    public function validasiKetersediaanUploadFenomenaMultiregional($id_prov, $id_pdrb, $tahun, $triwulan, $putaran, $revisi, $isi_fenom, $isi_sumber){
        if (FenomMultiregional::find()->where(['id_prov'=>$id_prov, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan, 'revisi'=>$revisi, 
            'isi_fenom'=>$isi_fenom, 'isi_sumber'=>$isi_sumber])->exists()){
            return 0;
        } 
        else {
            return 1;
        }
    }
 
    //Validasi apakah data PDRB telah tersimpan di DB
    public function validasiKetersediaanUploadDataProv($id_prov, $tahun, $triwulan, $putaran,$revisi){
        if(PdrbProv::find()->where(['id_prov' => $id_prov, 'tahun'=> $tahun, 'triwulan'=>$triwulan, 'putaran'=>$putaran , 'revisi' => $revisi])->exists()){
            return 0;
        }
            else {
                return 1;
            }
    }
    
    public function validasiKetersediaanUploadDataMultiregional($id_prov, $tahun, $triwulan, $putaran,$revisi){
        if(PdrbMultiregional::find()->where(['id_prov' => $id_prov, 'tahun'=> $tahun, 'triwulan'=>$triwulan, 'putaran'=>$putaran , 'revisi' => $revisi])->exists()){
            return 0;
        }
            else {
                return 1;
            }
    }

// Validasi Waktu Upload Data PDRB dan Fenomena
    
    //Validasi Waktu Pengunggahan file KNPR, Prov, Multiregional
    public function validasiUploadWaktuProv($tahun, $triwulan, $putaran, $revisi_file){
        $waktu_berjalan = Waktu::getWaktuBerjalan();
        $waktu_file = $tahun.'/'.$triwulan.'/'.$putaran;
        
    //BILA UPLOAD DATA UNTUK SAAT WAKTU BERJALAN (REAL TIME)
        if ($waktu_file === $waktu_berjalan){
            if($revisi_file == 0){
                return 1;
            } else {
                return 0;
            }
        }
        
    //BILA UPLOAD DATA UNTUK REVISI
        else{
            return($this->validasiUploadRevisiProv($waktu_berjalan, $tahun, $triwulan, $putaran, $revisi_file));
        }
        //BATAS CEK UPLOAD WAKTU DATA REVISI
    }
    
    public function validasiUploadRevisiProv($waktu_berjalan, $tahun_file, $triwulan_file, $putaran_file, $revisi_file){
        
            $waktu_berjalan= explode("/",$waktu_berjalan);
            $putaran_maks = Waktu::getPutaranMaks($tahun_file, $triwulan_file);
            
            // Triwulan 4, boleh revisi hingga 2 tahun ke belakang
            if ($waktu_berjalan[1] == 4){
                if ((int)$tahun_file == (int)$waktu_berjalan[0]) {
                    if ((int)$revisi_file <= (4 - (int)$triwulan_file) && $revisi_file >0 && (int)$putaran_file == $putaran_maks ) {
                        return 1;
                    } 
                    else {
                        return 0;
                    }
                }
                else if ((int)$tahun_file >= (int)$waktu_berjalan[0]-2 && (int)$tahun_file < (int)$waktu_berjalan[0]){
                    if ((int)$revisi_file > 0 && (int)$putaran_file == $putaran_maks ) {
                        return 1;
                    } 
                    else {
                        return 0;
                    }
                } 
                else {
                    return 0;
                }
            }
            else if ((int)$waktu_berjalan[1] < 4){
                if ((int)$triwulan_file < (int)$waktu_berjalan[1]){
                    if ((int)$revisi_file > 0 && $revisi_file <=((int)$waktu_berjalan[1] - (int)$triwulan_file) && (int)$putaran_file == $putaran_maks ){
                        return 1;
                    }
                    else {
                        return 0;
                    }
                }
                else {
                    return 0;
                }
            }
    }

//Validasi kesesuaian user untuk upload data
    public function validasiUploadUser($kode, $akses){
        //KNPR
        if ($akses == 1){
            if ($kode == '0100' || $kode == '0000'){
                return 1;
            }
            else {
                return 0;
            }
        }
        // Provinsi
        else if  ($akses == 2){
            if ($kode == Yii::$app->user->identity->kode_daerah){
                return 1;
            }
            else {
                return 0;
            }
        } 
        // Multiregional
        else if  ($akses == 3){
            if ($kode == Yii::$app->user->identity->kode_pdrb){
                return 1;
            }
            else {
                return 0;
            }
        } 
    }    

//Validasi balik arah laju pertumbuhan
    public function validasiUploadArahProv($tahun, $triwulan, $putaran, $id_prov, $id_pdrb, $pdrb_k_file, $kode_val){
    if ($kode_val == 1){
        if ($triwulan == 1){
            return 1;
        } else {
            $waktu = PdrbProv::getReferensiDataTerbaru($id_prov,$tahun,$triwulan-1);
            $pdrb_k_sebelum = PdrbProv::getAdhkByTime($id_prov,$id_pdrb,$waktu);
            if(!$pdrb_k_sebelum || $pdrb_k_sebelum->pdrb_k == 0){
                return 1;
            }
            (float)$laju_p = (((float)$pdrb_k_file/(float)$pdrb_k_sebelum->pdrb_k)*100)-100;
            if ($laju_p < 0){
                return 0;
            } else {
                return 1;
            }
        }
    } else {
        return 1;
        }
    }
    
    // PROCESSING DATA DAN FENOMENA
    
    //Memproses Data Provinsi untuk update turunan DB ; $array_model diperoleh dari $dataProvider
    public function processingDataUpdateProv($prov, $tahun, $triwulan, $waktu){
        $array_model = PdrbProv::find()->where(['id_prov' => $prov, 'tahun'=>$tahun, 'triwulan'=> $triwulan, 'timestamp'=>$waktu])->all();
            foreach($array_model as $model){
                $ref_q = PdrbProv::getReferensiQ($model->id_prov, $model->tahun, $model->triwulan);
                $ref_y = PdrbProv::getReferensiY($model->id_prov, $model->tahun, $model->triwulan);
                $ref_c_0 = PdrbProv::getReferensiC($model->id_prov, $model->tahun-1, $model->triwulan);
                $ref_c = PdrbProv::getReferensiC($model->id_prov, $model->tahun, $model->triwulan-1);
                if ($ref_q){
                    $laju_p_q = PdrbProv::getLajuPQ($model->id_prov, $model->id_pdrb, $ref_q, $model->pdrb_k);
                    $laju_imp_q = PdrbProv::getLajuImpQ($model->id_prov, $model->id_pdrb, $ref_q, $model->implisit);
                    $model->laju_p_q = $laju_p_q;
                    $model->laju_imp_q = $laju_imp_q; 
                }
                if($ref_q && $ref_y && $ref_c_0){
                    $laju_p_c = PdrbProv::getLajuPC($model->id_prov, $model->id_pdrb, $ref_c, $ref_c_0, $model->pdrb_k, $model->triwulan);
                    $laju_imp_c = PdrbProv::getLajuImpC($model->id_prov, $model->id_pdrb, $ref_c, $ref_c_0, $model->implisit, $model->triwulan);
                    $model->laju_p_c = $laju_p_c;
                    $model->laju_imp_c = $laju_imp_c;
                }
                if($ref_y){
                    $laju_p_y = PdrbProv::getLajuPY($model->id_prov, $model->id_pdrb, $ref_y, $model->pdrb_k);
                    $laju_imp_y = PdrbProv::getLajuImpY($model->id_prov, $model->id_pdrb, $ref_y, $model->implisit);
                    $model->laju_p_y = $laju_p_y;
                    $model->laju_imp_y = $laju_imp_y;
                }
                if($model->triwulan == 1){
                    $model->laju_p_c = $model->laju_p_y;
                }
                if($model->id_prov == '0100'){
                    $total_adhb = PdrbProv::getTotalADHBProv($model->id_pdrb, $model->tahun, $model->triwulan);
                    $total_adhk = PdrbProv::getTotalADHKProv($model->id_pdrb, $model->tahun, $model->triwulan);
                    if($model->pdrb_b != 0 || $model->pdrb_b != null){
                        $diskrepansi_b = ( (float)$total_adhb / (float)$model->pdrb_b  *100) - 100;
                        $model->diskrepansi_b = $diskrepansi_b;
                    } else {
                        $model->diskrepansi_b = null;
                        }
                    if($model->pdrb_k != 0 || $model->pdrb_k != null){
                        $diskrepansi_k = ((float)$total_adhk/ (float)$model->pdrb_k *100)- 100;
                        $model->diskrepansi_k = $diskrepansi_k;
                    } else{
                        $model->diskrepansi_k = null;
                    }
                } else if ($model->id_pdrb != '0100') {
                    $model->diskrepansi_b = null;
                    $model->diskrepansi_k = null;
                }
                if($model->id_pdrb === 'ZPDRB' || $model->id_pdrb === 'ZPDRBTP'){
                    if (    abs($model->diskrepansi_b) > 2 
                        || abs($model->diskrepansi_k) > 2 
                        || abs($model->share_b) > 10 
                        || abs($model->share_k) > 10 
                        || abs($model->laju_p_q) > 10 
                        || abs($model->laju_imp_q) > 10){
                        $model->flag = 1;
                        $model->status = "rekon";
                    } else {
                        $model->flag = 0;
                    }
                }
                else if($model->id_pdrb !== 'ZPDRB' || $model->id_pdrb !== 'ZPDRBTP'){
                    if (    abs($model->diskrepansi_b) > 5 
                            || abs($model->diskrepansi_k) > 5 
                            || abs($model->share_b) > 10 
                            || abs($model->share_k) > 10 
                            || abs($model->laju_p_q) > 10 
                            || abs($model->laju_imp_q) > 10){
                            $model->flag = 1;
                            $model->status = "rekon";
                        } else {
                            $model->flag = 0;
                        }
                }
                $model->save($runValidation = false);
            }
    }
    //Memproses Data Prov Tahunan untuk update turunan DB ; $array_model diperoleh dari $dataProvider
    public function processingDataUpdateProvTahun($id_prov, $getTahun, $getRefWaktu){
        $array_model = PdrbProvTahun::find()->where(['id_prov' => $id_prov, 'tahun'=>$getTahun, 'timestamp'=>$getRefWaktu])->all();
            foreach($array_model as $model){
                $ref_waktu = PdrbProvTahun::getReferensiTahunSebelum($model->id_prov, $model->tahun);
                    if ($ref_waktu){
                        $laju_p = PdrbProvTahun::getLajuP($model->id_prov, $model->id_pdrb, $ref_waktu, (float)$model->pdrb_k);
                        $laju_imp = PdrbProvTahun::getLajuImp($model->id_prov, $model->id_pdrb, $ref_waktu, $ind_imp);    
                        $model->laju_p = $laju_p;
                        $model->laju_imp = $laju_imp;
                    }
                    $total_adhb = PdrbKabkot::getTotalADHBKabkot($model->id_prov, $model->id_pdrb, $model->tahun);
                    $total_adhk = PdrbKabkot::getTotalADHKKabkot($model->id_prov, $model->id_pdrb, $model->tahun);
                    if($model->pdrb_b == 0 || $model->pdrb_b == null || $total_adhb == null){
                        $model->diskrepansi_b = null;
                    } else {
                        $diskrepansi_b = ( (float)$total_adhb / (float)$sum_adhb  *100) - 100;
                        $model->diskrepansi_b = $diskrepansi_b;
                        }
                    if($model->pdrb_k == 0 || $model->pdrb_k == null || $total_adhk == null){
                        $model->diskrepansi_k = null;
                    } else{
                        $diskrepansi_k = ((float)$total_adhk/ (float)$model->pdrb_k *100)- 100;
                        $model->diskrepansi_k = $diskrepansi_k;
                    }
                    
                    if($model->id_pdrb === 'ZPDRB' || $model->id_pdrb === 'ZPDRBTP'){
                        if (    abs($model->diskrepansi_b) > 2 
                            || abs($model->diskrepansi_k) > 2 
                            || abs($model->share_b) > 10 
                            || abs($model->share_k) > 10 
                            || abs($model->laju_p) > 10 
                            || abs($model->laju_imp) > 10){
                            $model->flag = 1;
                            $model->status = "rekon";
                        } else {
                            $model->flag = 0;
                        }
                    }
                else if($model->id_pdrb !== 'ZPDRB' || $model->id_pdrb !== 'ZPDRBTP'){
                    if (    abs($model->diskrepansi_b) > 5 
                            || abs($model->diskrepansi_k) > 5 
                            || abs($model->share_b) > 10 
                            || abs($model->share_k) > 10 
                            || abs($model->laju_p) > 10 
                            || abs($model->laju_imp) > 10){
                            $model->flag = 1;
                            $model->status = "rekon";
                    } else {
                        $model->flag = 0;
                        }
                }
                    $model->save(false);
                }
    } 
    
    //Input data tahunan ke pdrb_prov_tahun untuk data final
    public function processingDataProvTahun($id_prov, $getTahun){
        $timestamp = date('Y-m-d h:i:s');
        $list_pdrb = KBLI::find()->select('kode_pdrb')->all();
        $PDRB_b = PdrbProv::find()->where(['id_prov'=>$id_prov, 'id_pdrb'=>'ZPDRB', 'tahun'=>$getTahun, 'status'=>'final'])->sum('pdrb_b');
        $PDRB_k = PdrbProv::find()->where(['id_prov'=>$id_prov, 'id_pdrb'=>'ZPDRB', 'tahun'=>$getTahun, 'status'=>'final'])->sum('pdrb_k');
        foreach ($list_pdrb as $pdrb_object){
            $pdrb = $pdrb_object->kode_pdrb;
            $model = new PdrbProvTahun();
            $sum_adhb = PdrbProv::find()->where(['id_prov'=>$id_prov, 'id_pdrb'=>$pdrb, 'tahun'=>$getTahun, 'status'=>'final'])->sum('pdrb_b');
            $sum_adhk = PdrbProv::find()->where(['id_prov'=>$id_prov, 'id_pdrb'=>$pdrb, 'tahun'=>$getTahun, 'status'=>'final'])->sum('pdrb_k');
            $share_b = PdrbProvTahun::getDistPerB($PDRB_b, $sum_adhb);
            $share_k = PdrbProvTahun::getDistPerK($PDRB_k, $sum_adhk);
            $implisit = PdrbProvTahun::getIndImp($sum_adhb, $sum_adhk);
            $ref_waktu = PdrbProvTahun::getReferensiTahunSebelum($id_prov, $getTahun);
            $model->id_prov = $id_prov;
            $model->id_pdrb = $pdrb;
            $model->tahun = $getTahun;
            $model->pdrb_b = $sum_adhb;
            $model->pdrb_k = $sum_adhk;
            $model->share_b = $share_b;
            $model->share_k = $share_k;
            $model->implisit = $implisit;
            $model->timestamp = $timestamp;
            if($ref_waktu){
                $laju_p = PdrbProvTahun::getLajuP($id_prov, $id_pdrb, $ref_waktu, $sum_adhk);
                $laju_imp = PdrbProvTahun::getLajuImp($id_prov, $id_pdrb, $ref_waktu, $implisit);
                $model->laju_p = $laju_p;
                $model->laju_imp = $laju_imp;
            }
                    $total_adhb = PdrbKabkot::getTotalADHBKabkot($model->id_prov, $model->id_pdrb, $model->tahun);
                    $total_adhk = PdrbKabkot::getTotalADHKKabkot($model->id_prov, $model->id_pdrb, $model->tahun);
                    if($model->pdrb_b == 0 || $model->pdrb_b == null || $total_adhb == null){
                        $model->diskrepansi_b = null;
                    } else {
                        $diskrepansi_b = ( (float)$total_adhb / (float)$sum_adhb  *100) - 100;
                        $model->diskrepansi_b = $diskrepansi_b;
                        }
                    if($model->pdrb_k == 0 || $model->pdrb_k == null || $total_adhk == null){
                        $model->diskrepansi_k = null;
                    } else{
                        $diskrepansi_k = ((float)$total_adhk/ (float)$model->pdrb_k *100)- 100;
                        $model->diskrepansi_k = $diskrepansi_k;
                    }
                    if($model->id_pdrb === 'ZPDRB' || $model->id_pdrb === 'ZPDRBTP'){
                        if (    abs($model->diskrepansi_b) > 2 
                            || abs($model->diskrepansi_k) > 2 
                            || abs($model->share_b) > 10 
                            || abs($model->share_k) > 10 
                            || abs($model->laju_p) > 10 
                            || abs($model->laju_imp) > 10){
                            $model->flag = 1;
                            $model->status = "rekon";
                        } else {
                            $model->flag = 0;
                        }
                    }
                else if($model->id_pdrb !== 'ZPDRB' || $model->id_pdrb !== 'ZPDRBTP'){
                    if (    abs($model->diskrepansi_b) > 5 
                            || abs($model->diskrepansi_k) > 5 
                            || abs($model->share_b) > 10 
                            || abs($model->share_k) > 10 
                            || abs($model->laju_p) > 10 
                            || abs($model->laju_imp) > 10){
                            $model->flag = 1;
                            $model->status = "rekon";
                    } else {
                        $model->flag = 0;
                        }
                }
                    $model->save();
        }
    }
    
    //Memproses Data Provinsi hingga simpan DB (perhitungan turunan hingga save untuk data baru upload)
    public function processingDataUploadProv($file, $timestamp){
        $counter = 0; 
        $x = sizeof($file) - 2;
        $ref = $file[$x];
        $ref_zpdrb = explode(",", $ref);
        $zpdrb_b = $ref_zpdrb[7];
        $zpdrb_k = $ref_zpdrb[8];
            foreach($file as $data){
                if ($counter !=0){
                $modelnew = new PdrbProv;
                $hasil = explode(",",$data);
                $share_b = PdrbProv::getDistPerB($zpdrb_b, $hasil[7]);
                $share_k = PdrbProv::getDistPerK($zpdrb_k, $hasil[8]);
                $ind_imp = PdrbProv::getIndImp($hasil[7], $hasil[8]);
                $modelnew->id_prov = $hasil[0];
                $modelnew->id_pdrb = $hasil[1];
                $modelnew->tahun_dasar = $hasil[2];
                $modelnew->tahun = $hasil[3];
                $modelnew->triwulan = $hasil[4];
                $modelnew->putaran = $hasil[5];
                $modelnew->revisi = $hasil[6];
                $modelnew->pdrb_b = $hasil[7];
                $modelnew->pdrb_k = $hasil[8];
                $modelnew->share_b = $share_b;
                $modelnew->share_k = $share_k;
                $modelnew->implisit = $ind_imp;
                $ref_q = PdrbProv::getReferensiQ($hasil[0], $hasil[3], $hasil[4]);
                $ref_y = PdrbProv::getReferensiY($hasil[0], $hasil[3], $hasil[4]);
                $ref_c_0 = PdrbProv::getReferensiC($hasil[0], $hasil[3]-1, $hasil[4]);
                $ref_c = PdrbProv::getReferensiC($hasil[0], $hasil[3], $hasil[4]-1);
                
                if ($ref_q){
                    $laju_p_q = PdrbProv::getLajuPQ($hasil[0], $hasil[1], $ref_q, (float)$hasil[8]);
                    $laju_imp_q = PdrbProv::getLajuImpQ($hasil[0], $hasil[1], $ref_q, $ind_imp);    
                    $modelnew->laju_p_q = $laju_p_q;
                    $modelnew->laju_imp_q = $laju_imp_q;
                } 
                if($ref_y){
                    
                    $laju_p_y = PdrbProv::getLajuPY($hasil[0], $hasil[1], $ref_y, (float)$hasil[8]);
                    $laju_imp_y = PdrbProv::getLajuImpY($hasil[0], $hasil[1], $ref_y, $ind_imp);
                    $modelnew->laju_p_y = $laju_p_y;
                    $modelnew->laju_imp_y = $laju_p_y;
                }
                if($ref_q && $ref_y){
                    if($hasil[4]>1){
                        $laju_p_c = PdrbProv::getLajuPC($hasil[0], $hasil[1], $ref_c, $ref_c_0, (float)$hasil[8], $hasil[4]);
                        $laju_imp_c = PdrbProv::getLajuImpC($hasil[0], $hasil[1], $ref_c, $ref_c_0, $ind_imp, $hasil[4]);
                    } else {
                        $laju_p_c = $laju_p_y;
                        $laju_imp_c = $laju_imp_y;
                    }
                    $modelnew->laju_p_c = $laju_p_c;
                    $modelnew->laju_imp_c = $laju_imp_c;
                }
                if($model->triwulan == 1){
                    if($model->laju_p_y){
                    $modelnew->laju_p_c = $model->laju_p_y;
                    }
                }
                if($hasil[0] == '0100'){
                    $total_adhb = PdrbProv::getTotalADHBProv($hasil[1], $hasil[3], $hasil[4]);
                    $total_adhk = PdrbProv::getTotalADHKProv($hasil[1], $hasil[3], $hasil[4]);
                    if($hasil[7] == 0 ||$hasil[7] == null){
                        $modelnew->diskrepansi_b = null;
                     } else {
                        $diskrepansi_b = ((float)$total_adhb / (float)$hasil[7] *100) - 100;
                        $modelnew->diskrepansi_b = $diskrepansi_b;
                        }
                    if($hasil[8] != 0 || $hasil[8] != null){
                        $modelnew->diskrepansi_k = null;
                    } else{
                        $diskrepansi_k = ((float)$total_adhk / (float)$hasil[8]  *100)- 100;
                        $modelnew->diskrepansi_k = $diskrepansi_k;
                    }
                } else if ($hasil[0] != '0100') {
                    $modelnew->diskrepansi_b = null;
                    $modelnew->diskrepansi_k = null;
                }
                
                if($hasil[1] === 'ZPDRB' || $hasil[1] === 'ZPDRBTP'){
                    if (    abs($modelnew->diskrepansi_b) > 2 
                        || abs($modelnew->diskrepansi_k) > 2 
                        || abs($modelnew->share_b) > 10 
                        || abs($modelnew->share_k) > 10 
                        || abs($modelnew->laju_p_q) > 10 
                        || abs($modelnew->laju_imp_q) > 10){
                        $modelnew->flag = 1;
                        $modelnew->status = "rekon";
                    } else {
                        $modelnew->flag = 0;
                    }
                }
                else if($hasil[1] !== 'ZPDRB' || $hasil[1] !== 'ZPDRBTP'){
                    if (    abs($modelnew->diskrepansi_b) > 5 
                            || abs($modelnew->diskrepansi_k) > 5
                            || abs($modelnew->share_b) > 10 
                            || abs($modelnew->share_k) > 10 
                            || abs($modelnew->laju_p_q) > 10 
                            || abs($modelnew->laju_imp_q) > 10){
                            $modelnew->flag = 1;
                            $modelnew->status = "rekon";
                    } else {
                        $modelnew->flag = 0;
                        }
                }
                if ($timestamp){
                        $modelnew->timestamp= $timestamp;
                    }
                    $modelnew->save();
                }
                $counter++;
            }
    }

    //Memproses Data PDRB Multiregional hingga simpan DB (perhitungan turunan hingga save untuk data baru upload)
    public function processingDataUploadMultiregional($file, $timestamp){
        $counter = 0;    
            foreach($file as $data){
                if($counter != 0){
                    $modelnew = new PdrbMultiregional;
                    $hasil = explode(",",$data);

                    $modelnew->id_prov = $hasil[0];
                    $modelnew->id_pdrb = $hasil[1];
                    $modelnew->tahun_dasar = $hasil[2];
                    $modelnew->tahun = $hasil[3];
                    $modelnew->triwulan = $hasil[4];
                    $modelnew->putaran = $hasil[5];
                    $modelnew->revisi = $hasil[6];
                    $modelnew->pdrb_b = $hasil[7]; 
                    $modelnew->pdrb_k = $hasil[8];

                    if ($timestamp){
                            $modelnew->timestamp= $timestamp;
                        }
                    $modelnew->save();
                }
                $counter++;
            }    
    }
    
    //Memproses Fenomena Provinsi hingga simpan DB
    public function processingFenomenaUploadProv($file, $timestamp){
        $counter = 0;    
        foreach($file as $data){
            if($counter != 0){
                $modelnew = new FenomProv;
                $hasil = explode(",",$data);
 
                $modelnew->id_prov = $hasil[0];
                $modelnew->id_pdrb = $hasil[1];
                $modelnew->tahun = $hasil[2];
                $modelnew->triwulan = $hasil[3];
                $modelnew->putaran = $hasil[4];
                $modelnew->revisi = $hasil[5];
                $modelnew->isi_fenom = $hasil[6]; 
                $modelnew->isi_tipe = $hasil[7];
                $modelnew->isi_sumber = $hasil[8];
                $modelnew->isi_indikasi = $hasil[9];
                
                if ($timestamp){
                        $modelnew->timestamp= $timestamp;
                    }
                $modelnew->save(false);
            }
            $counter++;
        }    
    }
    
    //Memproses Fenomena Multiregional hingga simpan DB
    public function processingFenomenaUploadMultiregional($file, $timestamp){
        $counter = 0;    
        foreach($file as $data){
            if($counter != 0){
                $modelnew = new FenomMultiregional;
                $hasil = explode(",",$data);
 
                $modelnew->id_prov = $hasil[0];
                $modelnew->id_pdrb = $hasil[1];
                $modelnew->tahun = $hasil[2];
                $modelnew->triwulan = $hasil[3];
                $modelnew->putaran = $hasil[4];
                $modelnew->revisi = $hasil[5];
                $modelnew->isi_fenom = $hasil[6]; 
                $modelnew->isi_tipe = $hasil[7];
                $modelnew->isi_sumber = $hasil[8];
                $modelnew->isi_indikasi = $hasil[9];
                
                if ($timestamp){
                        $modelnew->timestamp= $timestamp;
                    }
                $modelnew->save(false);
            }
            $counter++;
        }    
    }

    
// Mendapatkan Data
     
    //Provider data untuk tabel diskrepansi PDB-PDRB Provinsi
    public function getDataDiskreProv($getProv,$getTahun,$getTriwulan,$getRefWaktu){
        if($getTriwulan == 1){
                $tahun_0 = $getTahun - 1;
                $triwulan_0 = 4;
                $getRefWaktu_0 = PdrbProv::getReferensiDataTerbaru($getProv, $tahun_0, $triwulan_0); 
            }
            else {
                $tahun_0 = $getTahun;
                $triwulan_0 = $getTriwulan-1;
            }
            $getRefWaktu_0 = PdrbProv::getReferensiDataTerbaru($getProv, $tahun_0, $triwulan_0); 
            
            if ($getRefWaktu && $getRefWaktu_0){
            $totalCount = PdrbProv::find()->where([
                'id_prov'=>$getProv,
                'tahun' =>$getTahun,
                'triwulan' => $getTriwulan,
                'timestamp' => $getRefWaktu
                    ])->count();
            
        $dataProvider = new SqlDataProvider([
            'db' => Yii::$app->db,
            'sql' => "SELECT * "
                . "FROM (SELECT * FROM pdrb_prov "
                . "WHERE id_prov = '".$getProv
                ."' AND tahun = ".$getTahun
                ." AND triwulan = ".$getTriwulan
                ." And timestamp = '".$getRefWaktu
                ."') table_1 "
                . "LEFT JOIN "
                . " (SELECT "
                . "id_pdrb, "
                . "share_b AS share_b_0, "
                . "share_k AS share_k_0, "
                . "laju_p_q AS laju_p_q_0, "
                . "laju_p_y AS laju_p_y_0, "
                . "laju_p_c AS laju_p_c_0, "
                . "implisit AS implisit_0, "
                . "laju_imp_q AS laju_imp_q_0, "
                . "laju_imp_y AS laju_imp_y_0, "
                . "laju_imp_c AS laju_imp_c_0 "
                . "FROM pdrb_prov "
                . "WHERE id_prov = '".$getProv
                ."' AND tahun = ".$tahun_0
                ." AND triwulan = ".$triwulan_0
                ." And timestamp = '".$getRefWaktu_0
                ."') table_2 "
                . "ON table_1.id_pdrb = table_2.id_pdrb "
                . "LEFT JOIN "
                . " (SELECT "
                . "kode_daerah, nama_provinsi "
                . "FROM m_daerah "
                . "WHERE kode_daerah = '".$getProv
                ."') table_3 "
                . "ON table_1.id_prov = table_3.kode_daerah "
                . "ORDER BY table_1.id_pdrb",
            'totalCount' => $totalCount,
            'pagination' => [
            'pageSize' => $totalCount,
                    ],
            'sort' =>false,
            ]);
            return $dataProvider;
            }
            else if ($getRefWaktu && !$getRefWaktu_0){
            $totalCount = PdrbProv::find()->where([
                'id_prov'=>$getProv,
                'tahun' =>$getTahun,
                'triwulan' => $getTriwulan,
                'timestamp' => $getRefWaktu
                    ])->count();
            
        $dataProvider = new SqlDataProvider([
            'db' => Yii::$app->db,
            'sql' => "SELECT * "
                . "FROM (SELECT * FROM pdrb_prov "
                . "WHERE id_prov = '".$getProv
                ."' AND tahun = ".$getTahun
                ." AND triwulan = ".$getTriwulan
                ." And timestamp = '".$getRefWaktu
                ."') table_1 "
                . "LEFT JOIN "
                . " (SELECT "
                . "kode_daerah, nama_provinsi "
                . "FROM m_daerah "
                . "WHERE kode_daerah = '".$getProv
                ."') table_2 "
                . "ON table_1.id_prov = table_2.kode_daerah "
                . "ORDER BY table_1.id_pdrb",
            'totalCount' => $totalCount,
            'pagination' => [
            'pageSize' => $totalCount,
                    ],
            'sort' =>false,
            ]);
            
            return $dataProvider;
        } else {
         return null;   
        }
    }
    
    //Fungsi Penyedia data Regnas Diskrepansi PDRB
    public function getDataDiskrePdrbProv($getPdrb, $getTahun, $getTriwulan){            
            if($getTriwulan == 1){
                $tahun_0 = $getTahun - 1;
                $triwulan_0 = 4;
            }
            else {
                $tahun_0 = $getTahun;
                $triwulan_0 = $getTriwulan-1;
            }
            if(PdrbProv::find()->where(['id_pdrb'=>$getPdrb, 'tahun'=>$tahun_0, 'triwulan'=>$triwulan_0])->exists()){
                    $totalCount = Daerah::find()->where(['kode_kab'=>'00'])->count();
                    $dataProvider = new SqlDataProvider([
                    'db' => Yii::$app->db,
                    'sql' => "SELECT * "
                        . "FROM "
                        . "(SELECT id_prov,  id_pdrb, tahun,  triwulan, MAX(timestamp)  "
                        . "FROM pdrb_prov "
                        . "WHERE id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$getTahun
                        ." AND triwulan = ".$getTriwulan
                        ." GROUP BY id_prov, id_pdrb, tahun, triwulan ) table_1 "
                        . "LEFT JOIN "
                        . " (SELECT "
                        . "kode_daerah, nama_provinsi "
                        . "FROM m_daerah "
                        .") table_2 "
                        . "ON table_1.id_prov = table_2.kode_daerah "
                        . "LEFT JOIN "
                        . " (SELECT id_prov, status,  timestamp,  "
                        . "pdrb_b,  pdrb_k,  share_b,  share_k, laju_p_q,  laju_p_y,  laju_p_c,  implisit,  "
                        . "laju_imp_q,  laju_imp_c,  laju_imp_y,  diskrepansi_b, diskrepansi_k, flag  "
                        . "FROM pdrb_prov "
                        . "WHERE id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$getTahun
                        ." AND triwulan = ".$getTriwulan
                        .") table_3 "
                        . "ON table_1.id_prov = table_3.id_prov "
                        . "LEFT JOIN "
                        . "(SELECT id_prov,  id_pdrb, tahun,  triwulan, MAX(timestamp) AS timestamp  "
                        . "FROM pdrb_prov "
                        . "WHERE id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$tahun_0
                        ." AND triwulan = ".$triwulan_0
                        ." GROUP BY id_prov, id_pdrb, tahun, triwulan ) table_4 "
                        . " ON table_1.id_prov = table_4.id_prov "
                        . "LEFT JOIN "
                        . " (SELECT "
                        . "id_prov, timestamp, "
                        . "share_b AS share_b_0, "
                        . "share_k AS share_k_0, "
                        . "laju_p_q AS laju_p_q_0, "
                        . "laju_p_y AS laju_p_y_0, "
                        . "laju_p_c AS laju_p_c_0, "
                        . "implisit AS implisit_0, "
                        . "laju_imp_q AS laju_imp_q_0, "
                        . "laju_imp_y AS laju_imp_y_0, "
                        . "laju_imp_c AS laju_imp_c_0 "
                        . "FROM pdrb_prov "
                        . "WHERE id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$tahun_0
                        ." AND triwulan = ".$triwulan_0
                        .") table_5 "
                        . "ON table_4.id_prov = table_5.id_prov AND table_4.timestamp = table_5.timestamp "
                        . " ORDER BY table_1.id_prov",
                    'totalCount' => $totalCount,
                    'pagination' => [
                    'pageSize' => $totalCount,
                            ],
                    'sort' =>false,
                    ]);

                    return $dataProvider;
                    
                } else {
                    $totalCount = Daerah::find()->where(['kode_kab'=>'00'])->count();
                    $dataProvider = new SqlDataProvider([
                    'db' => Yii::$app->db,
                    'sql' => "SELECT * "
                        . "FROM "
                        . "(SELECT id_prov,  id_pdrb, tahun,  triwulan, MAX(timestamp)  "
                        . "FROM pdrb_prov "
                        . "WHERE id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$getTahun
                        ." AND triwulan = ".$getTriwulan
                        ." GROUP BY id_prov, id_pdrb, tahun, triwulan ) table_1 "
                        . "LEFT JOIN "
                        . " (SELECT "
                        . "kode_daerah, nama_provinsi "
                        . "FROM m_daerah "
                        .") table_2 "
                        . "ON table_1.id_prov = table_2.kode_daerah "
                        . "LEFT JOIN "
                        . " (SELECT id_prov, status,  timestamp,  "
                        . "pdrb_b,  pdrb_k,  share_b,  share_k, laju_p_q,  laju_p_y,  laju_p_c,  implisit,  "
                        . "laju_imp_q,  laju_imp_c,  laju_imp_y,  diskrepansi_b, diskrepansi_k, flag  "
                        . "FROM pdrb_prov "
                        . "WHERE id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$getTahun
                        ." AND triwulan = ".$getTriwulan
                        .") table_3 "
                        . "ON table_1.id_prov = table_3.id_prov"
                        . " ORDER BY table_1.id_prov",
                    'totalCount' => $totalCount,
                    'pagination' => [
                    'pageSize' => $totalCount,
                            ],
                    'sort' =>false,
                    ]);
                    return $dataProvider;
        }
    }
    
    //Dapatkan data Provinsi Tahunan
    public function getDataProvTahun($getProv, $getTahun, $getRefWaktu){
        $tahun_0 = $getTahun - 1;
        $getRefWaktu_0 = PdrbProvTahun::getReferensiTahunSebelum($getProv, $getTahun); 
                    
            if ($getRefWaktu && $getRefWaktu_0){
            $totalCount = PdrbProvTahun::find()->where([
                'id_prov'=>$getProv,
                'tahun' =>$getTahun,
                'timestamp' => $getRefWaktu
                    ])->count();
            
        $dataProvider = new SqlDataProvider([
            'db' => Yii::$app->db,
            'sql' => "SELECT * "
                . "FROM (SELECT * FROM pdrb_prov_tahun "
                . "WHERE id_prov = '".$getProv
                ."' AND tahun = ".$getTahun
                ." And timestamp = '".$getRefWaktu
                ."') table_1 "
                . "LEFT JOIN "
                . " (SELECT "
                . "id_pdrb, "
                . "share_b AS share_b_0, "
                . "share_k AS share_k_0, "
                . "laju_p AS laju_p_0, "
                . "implisit AS implisit_0, "
                . "laju_imp AS laju_imp_0, "
                . "FROM pdrb_prov_tahun "
                . "WHERE id_prov = '".$getProv
                ."' AND tahun = ".$tahun_0
                ." And timestamp = '".$getRefWaktu_0
                ."') table_2 "
                . "ON table_1.id_pdrb = table_2.id_pdrb "
                . "LEFT JOIN "
                . " (SELECT "
                . "kode_daerah, nama_provinsi "
                . "FROM m_daerah "
                . "WHERE kode_daerah = '".$getProv
                ."') table_3 "
                . "ON table_1.id_prov = table_3.kode_daerah "
                . "ORDER BY table_1.id_pdrb",
            'totalCount' => $totalCount,
            'pagination' => [
            'pageSize' => $totalCount,
                    ],
            'sort' =>false,
            ]);
            return $dataProvider;
            }
            else if ($getRefWaktu && !$getRefWaktu_0){
            $totalCount = PdrbProvTahun::find()->where([
                'id_prov'=>$getProv,
                'tahun' =>$getTahun,
                'timestamp' => $getRefWaktu
                    ])->count();
            
        $dataProvider = new SqlDataProvider([
            'db' => Yii::$app->db,
            'sql' => "SELECT * "
                . "FROM (SELECT * FROM pdrb_prov_tahun "
                . "WHERE id_prov = '".$getProv
                ."' AND tahun = ".$getTahun
                ." And timestamp = '".$getRefWaktu
                ."') table_1 "
                . "LEFT JOIN "
                . " (SELECT "
                . "kode_daerah, nama_provinsi "
                . "FROM m_daerah "
                . "WHERE kode_daerah = '".$getProv
                ."') table_2 "
                . "ON table_1.id_prov = table_2.kode_daerah "
                . "ORDER BY table_1.id_pdrb",
            'totalCount' => $totalCount,
            'pagination' => [
            'pageSize' => $totalCount,
                    ],
            'sort' =>false,
            ]);
            
            return $dataProvider;
        } else {
         return null;   
        }
    }
    
    //Dapatkan data multiregional
    public function getDataMultiregional($getPdrb, $getTahun, $getTriwulan){
        $totalCount = Daerah::find()->where(['kode_kab'=>'00'])->count();
                    $dataProvider = new SqlDataProvider([
                    'db' => Yii::$app->db,
                    'sql' => "SELECT * "
                        . "FROM "
                        . "(SELECT id_prov,  id_pdrb, tahun,  triwulan, MAX(timestamp)  "
                        . "FROM pdrb_multiregional "
                        . "WHERE id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$getTahun
                        ." AND triwulan = ".$getTriwulan
                        ." GROUP BY id_prov, id_pdrb, tahun, triwulan ) table_1 "
                        . "LEFT JOIN "
                        . " (SELECT "
                        . "kode_daerah, nama_provinsi "
                        . "FROM m_daerah "
                        .") table_2 "
                        . "ON table_1.id_prov = table_2.kode_daerah "
                        . "LEFT JOIN "
                        . " (SELECT id_prov, timestamp,  "
                        . "pdrb_b,  pdrb_k, "
                        . "FROM pdrb_multiregional "
                        . "WHERE id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$getTahun
                        ." AND triwulan = ".$getTriwulan
                        .") table_3 "
                        . "ON table_1.id_prov = table_3.id_prov "
                        . " ORDER BY table_1.id_prov",
                    'totalCount' => $totalCount,
                    'pagination' => [
                    'pageSize' => $totalCount,
                            ],
                    'sort' =>false,
                    ]);

                    return $dataProvider;
    }
    
    public function getFenomenaMultiregional($getPdrb, $getTahun, $getTriwulan){
        $totalCount = FenomMultiregional::find()->where(['id_pdrb'=>$getPdrb, 'tahun'=>$getTahun, 'triwulan'=>$getTriwulan])->count();
                    $dataProvider = new SqlDataProvider([
                    'db' => Yii::$app->db,
                    'sql' => "SELECT * "
                        . "FROM "
                        . "(SELECT id_prov, id_pdrb, timestamp,  "
                        . "isi_fenom, isi_tipe, isi_sumber, isi_indikasi "
                        . "FROM fenom_multiregional "
                        . "WHERE id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$getTahun
                        ." AND triwulan = ".$getTriwulan
                        ." ) table_1 "
                        . "LEFT JOIN "
                        . " (SELECT "
                        . "kode_daerah, nama_provinsi "
                        . "FROM m_daerah "
                        .") table_2 "
                        . "ON table_1.id_prov = table_2.kode_daerah "
                        . "ORDER BY table_1.id_prov",
                    'totalCount' => $totalCount,
                    'pagination' => [
                    'pageSize' => $totalCount,
                            ],
                    'sort' =>false,
                    ]);

                    return $dataProvider;
    }
    
    //Dapatkan data untuk adjusment rekonsiliasi Regnas
    public function getDataAdjustPdrbProv($getProv, $getPdrb, $getTahun, $getTriwulan, $tahun_0, $triwulan_0){
            $ref = PdrbProv::getReferensiDataTerbaru($getProv, $getTahun, $getTriwulan);
            $ref_0 = PdrbProv::getReferensiQ($getProv, $getTahun, $getTriwulan);
            $ref_pdb = PdrbProv::getReferensiPDB($getTahun, $getTriwulan);
            $totalCount = 2;
                    $dataProvider = new SqlDataProvider([
                    'db' => Yii::$app->db,
                    'sql' => "SELECT * "
                        . "FROM "
                        . "(SELECT id_prov,  id_pdrb, tahun,  triwulan, pdrb_b, pdrb_k, implisit, share_b, share_k, laju_p_q AS laju_p, laju_imp_q AS laju_implisit, id_adjust"
                        . " FROM pdrb_prov, m_adjust "
                        . "WHERE id_prov = '".$getProv
                        . "' AND id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$getTahun
                        ." AND triwulan = ".$getTriwulan
                        ." AND  timestamp = '".$ref
                        . "' ) table_1 "
                        . "LEFT JOIN "
                        . " (SELECT pdrb_b AS pdrb_b_0, pdrb_k AS pdrb_k_0, implisit AS implisit_0, id_adjust"
                        . " FROM pdrb_prov, m_adjust "
                        . "WHERE id_prov = '".$getProv
                        . "' AND id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$tahun_0
                        ." AND triwulan = ".$triwulan_0
                        ." AND  timestamp = '".$ref_0
                        . "') table_2 "
                        . "ON table_1.id_adjust = table_2.id_adjust "
                        . "LEFT JOIN "
                        . " (SELECT "
                        . "kode_daerah, nama_provinsi "
                        . " FROM m_daerah "
                        .") table_3 "
                        . "ON table_1.id_prov = table_3.kode_daerah "
                        . "LEFT JOIN "
                        . " (SELECT id_pdrb, pdrb_b AS pdb_b, pdrb_k AS pdb_k, id_adjust, diskrepansi_b, diskrepansi_k"
                        . " FROM pdrb_prov, m_adjust "
                        . "WHERE id_prov = '0100'"
                        . " AND id_pdrb = '".$getPdrb
                        ."' AND tahun = ".$getTahun
                        ." AND triwulan = ".$getTriwulan
                        ." AND  timestamp = '".$ref_pdb
                        ."') table_4 "
                        . "ON table_1.id_adjust = table_4.id_adjust "
                        . "LEFT JOIN "
                        . "(SELECT pdrb_b AS zpdrb_b, pdrb_k AS zpdrb_k, id_adjust"
                        . " FROM pdrb_prov, m_adjust "
                        . "WHERE id_prov = '".$getProv
                        . "' AND id_pdrb = 'ZPDRB"
                        ."' AND tahun = ".$getTahun
                        ." AND triwulan = ".$getTriwulan
                        ." AND  timestamp = '".$ref
                        . "' ) table_5 "
                        . "ON table_1.id_adjust = table_5.id_adjust ",
                    'totalCount' => $totalCount,
                    'pagination' => [
                    'pageSize' => $totalCount,
                            ],
                    'sort' =>false,
                    ]);
            return $dataProvider;
    }
    
    //Memproses data Prov untuk finalisasi data PDRB
    public function processingDataFinalisasiProv($prov, $tahun, $triwulan, $waktu){
        if(!PdrbProv::find()->where(['id_prov' => $prov, 'tahun'=>$tahun, 'triwulan'=> $triwulan, 'timestamp'=>$waktu, 'status'=>'final'])->exists()){
        $array_model = PdrbProv::find()->where(['id_prov' => $prov, 'tahun'=>$tahun, 'triwulan'=> $triwulan, 'timestamp'=>$waktu])->all();
            foreach($array_model as $model){
                $model->status = 'final';
                $model->save(false);    
            }
        }
        else {
            $error = [];
            $error[] = "Maaf, data untuk triwulan tersebut sudah ada yang final. Harap hapus finalisasi terlebih dahulu";
            return $this->redirect(['site/error-page', 'error' => $error]);
        }
    }
    
        //Memproses data Prov untuk definalisasi data PDRB
    public function processingDataDefinalisasiProv($prov, $tahun, $triwulan, $waktu){
        if(PdrbProv::find()->where(['id_prov' => $prov, 'tahun'=>$tahun, 'triwulan'=> $triwulan, 'timestamp'=>$waktu, 'status'=>'final'])->exists()){
        $array_model = PdrbProv::find()->where(['id_prov' => $prov, 'tahun'=>$tahun, 'triwulan'=> $triwulan, 'timestamp'=>$waktu, 'status'=>'final'])->all();
            foreach($array_model as $model){
                $model->status = '';
                $model->save(false);    
            }
        }
        else {
            $error = [];
            $error[] = "Maaf, data untuk triwulan tersebut belum ada yang final. Harap lakukan finalisasi terlebih dahulu";
            return $this->redirect(['site/error-page', 'error' => $error]);
        }
    }
    
    //Penyedia kolom untuk Gridview Diskrepansi Regnas
    public function getKolomRegnasDiskrepansi(){
        $kolom = [
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'id_prov',
                'label' => 'Id',
                'header' => '<center> Id </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'nama_provinsi',
                'label' => 'Provinsi',
                'header' => '<center> Provinsi </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'tahun',
                'label' => 'Tahun',
                'header' => '<center> Tahun </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'triwulan',
                'label' => 'Triwulan',
                'header' => '<center> Triwulan </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'id_pdrb',
                'label' => 'PDRB',
                'header' => '<center> Id PDRB </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'pdrb_b',
                'label' => 'ADHB',
                'header' => '<center> ADHB </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'pdrb_k',
                'label' => 'ADHK'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'diskrepansi_b',
                'label' => 'Diskrepansi B',
                'header' => '<center> Diskrepansi B <br> PDB-PDRB </center>',
                'contentOptions' => function ($model) {
                        if($model['id_pdrb'] == 'ZPDRB' || $model['id_pdrb'] == 'ZPDRB'){
                            if(abs($model['diskrepansi_b'])> 2){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                        } 
                        else {
                            if(abs($model['diskrepansi_b'])> 5){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                        }
                    }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'diskrepansi_k',
                'label' => 'Diskrepansi K',
                'header' => '<center> Diskrepansi K <br> PDB-PDRB </center>',
                'contentOptions' => function ($model) {
                        if($model['id_pdrb'] == 'ZPDRB' || $model['id_pdrb'] == 'ZPDRB'){
                            if(abs($model['diskrepansi_k'])> 2){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                        } 
                        else {
                            if(abs($model['diskrepansi_k'])> 5){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                        }
                    }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'share_b',
                'label' => 'StrukturEkoK Sekarang',
                'header' => '<center> Struktur Ekonomi <br> ADHB <br> Sekarang </center>',
                'contentOptions' => function ($model) {
                            if(abs($model['share_b'])> 10){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                    }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'share_b_0',
                'label' => 'StrukEkoB Sebelum',
                'header' => '<center> Struktur Ekonomi <br> ADHB <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'share_k',
                'label' => 'StrukturEkoK Sekarang',
                'header' => '<center> Struktur Ekonomi <br> ADHK <br> Sekarang </center>',
                'contentOptions' => function ($model) {
                            if(abs($model['share_k'])> 10){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                    }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'share_k_0',
                'label' => 'StrukEkoK Sebelum',
                'header' => '<center> Struktur Ekonomi <br> ADHK <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_q',
                'label' => 'Laju Pertumbuhan (q-to-q) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> (q-to-q) <br> Sekarang </center>',
                'contentOptions' => function ($model) {
                            if(abs($model['laju_p_q'])> 10){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                    }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_q_0',
                'label' => 'Laju Pertumbuhan (q-to-q) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> (q-to-q) <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_y',
                'label' => 'Laju Pertumbuhan (y-to-y) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> (y-to-y) <br> Sekarang </center>',
                'contentOptions' => function ($model) {
                            if(abs($model['laju_p_y'])> 10){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                    }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_y_0',
                'label' => 'Laju Pertumbuhan (q-to-q) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> (y-to-y) <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_c',
                'label' => 'Laju Pertumbuhan (c-to-c) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> (c-to-c) <br> Sekarang </center>',
                'contentOptions' => function ($model) {
                            if(abs($model['laju_p_c'])> 10){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                    }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_p_c_0',
                'label' => 'Laju Pertumbuhan (c-to-c) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> (c-to-c) <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'implisit',
                'label' => 'Indeks Implisit Sekarang',
                'header' => '<center> Indeks Implisit <br> Sekarang </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'implisit_0',
                'label' => 'Indeks Implisit Sebelum',
                'header' => '<center> Indeks Implisit <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_q',
                'label' => 'Laju Pertumbuhan Implisit (q-to-q) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (q-to-q) <br> Sekarang </center>',
                'contentOptions' => function ($model) {
                            if(abs($model['laju_imp_q'])> 10){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                    }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_q_0',
                'label' => 'Laju Pertumbuhan Implisit (q-to-q) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (q-to-q) <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_y',
                'label' => 'Laju Pertumbuhan Implisit (y-to-y) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (y-to-y) <br> Sekarang </center>',
                'contentOptions' => function ($model) {
                            if(abs($model['laju_imp_y'])> 10){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                    }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_y_0',
                'label' => 'Laju Pertumbuhan Implisit (y-to-y) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (y-to-y) <br> Sebelum </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_c',
                'label' => 'Laju Pertumbuhan Implisit (c-to-c) Sekarang',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (c-to-c) <br> Sekarang </center>',
                'contentOptions' => function ($model) {
                            if(abs($model['laju_imp_c'])> 10){
                                return ['style' => 'background-color: yellow'];    
                            } else {
                                return ['style' => ''];
                            }
                    }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'laju_imp_c_0',
                'label' => 'Laju Pertumbuhan Implisit (c-to-c) Sebelum',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (c-to-c) <br> Sebelum </center>'
            ],
            [
                'class' => '\kartik\grid\BooleanColumn',
                'attribute' => 'flag',
                'trueLabel' => 1, 
                'falseLabel' => 0,
                'trueIcon' =>  '<span class="glyphicon glyphicon-warning-sign"></span>',
                'falseIcon' => '<span class="glyphicon glyphicon-ok"></span>'   
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'status',
                'label' => 'Status',
                'header' => '<center> Status </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'timestamp',
                'label' => 'Timestamp',
                'header' => '<center> Waktu Upload </center>'
            ]
        ];
    return $kolom;
    }
    
    //Penyedia kolom rekonsiliasi
    public function getKolomRegnasRekonsiliasi(array $adjust, $total_adhb_selain, $total_adhk_selain, $total_adhb, $total_adhk){        
        $kolom = [
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'id_adjust',
                'label' => 'Adjust',
                'header' => '<center>  </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'id_prov',
                'label' => 'Id Prov',
                'header' => '<center> Id Prov</center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'nama_provinsi',
                'label' => 'Provinsi',
                'header' => '<center> Provinsi </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'id_pdrb',
                'label' => 'PDRB',
                'header' => '<center> Id PDRB </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'pdrb_b',
                'label' => 'ADHB',
                'header' => '<center> ADHB </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'Adjust ADHB',
                'header' => 'Adjust ADHB',
                'value' => function($model) use($adjust){
                    return $adjust[0]; 
                }
            ],
            [
                'class' => '\kartik\grid\FormulaColumn',
                'header' => 'Adjusted ADHB',
                'value' => function ($model, $key, $index, $widget) {
                    $p = compact('model', 'key', 'index');
                    return $widget->col(4, $p) + $widget->col(5, $p);
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'pdrb_k',
                'label' => 'ADHK',
                'header' => '<center> ADHK </center>'
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'Adjust ADHK',
                'header' => 'Adjust ADHK',
                'value' => function($model) use($adjust){
                    if($model['id_adjust'] == 'Diskrepansi'){
                    return $adjust[1];
                    } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                        return $adjust[1];
                    }
                }
            ],
            [
                'class' => '\kartik\grid\FormulaColumn',
                'header' => 'Adjusted ADHK',
                'value' => function ($model, $key, $index, $widget) {
                    $p = compact('model', 'key', 'index');
                    return $widget->col(7, $p) + $widget->col(8, $p);
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Diskrepansi ADHB',
                'header' => '<center> Diskrepansi ADHB </center>',
                'value' => function($model) use ($total_adhb){
                    if($model['id_adjust'] == 'Diskrepansi'){
                    return $total_adhb - $model['pdb_b'];
                    } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                        return $model['diskrepansi_b'];
                    }
                }
            ],
            [
                'class' => '\kartik\grid\FormulaColumn',
                'header' => '<center> Adjusted Diskrepansi <br> ADHB </center>',
                'value' => function ($model, $key, $index, $widget) use ($total_adhb_selain) {
                    $p = compact('model', 'key', 'index');
                    if($model['pdb_b'] != 0 || $model['pdb_b'] != null ){
                        if($model['id_adjust'] == 'Diskrepansi'){
                            $hitung = $widget->col(6, $p) + $total_adhb_selain;
                            $diskrepansi_b_adjusted = (float)$hitung - (float)$model['pdb_b'];
                            return $diskrepansi_b_adjusted ;
                            } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                                $hitung = $widget->col(6, $p) + $total_adhb_selain;
                                $diskrepansi_b_adjusted = ((float)$hitung / (float)$model['pdb_b'] *100) -100;
                                return $diskrepansi_b_adjusted;
                            }    
                    } else {
                        return null;
                    }
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Diskrepansi ADHK',
                'header' => '<center> Diskrepansi ADHK </center>',
                'value' => function($model) use ($total_adhk){
                    if($model['id_adjust'] == 'Diskrepansi'){
                    return $total_adhk - $model['pdb_b'];
                    } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                        return $model['diskrepansi_k'];
                    }
                }
            ],
            [
                'class' => '\kartik\grid\FormulaColumn',
                'header' => '<center> Adjusted Diskrepansi <br> ADHK </center>',
                'value' => function ($model, $key, $index, $widget) use ($total_adhk_selain) {
                    $p = compact('model', 'key', 'index');
                    if($model['pdb_b'] != 0 || $model['pdb_b'] != null ){
                        if($model['id_adjust'] == 'Diskrepansi'){
                            $hitung = $widget->col(9, $p) + $total_adhk_selain;
                            $diskrepansi_k_adjusted = (float)$hitung - (float)$model['pdb_k'];
                            return $diskrepansi_k_adjusted ;
                            } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                                $hitung = $widget->col(9, $p) + $total_adhk_selain;
                                $diskrepansi_k_adjusted = ((float)$hitung / (float)$model['pdb_k'] *100) -100;
                                return $diskrepansi_k_adjusted;
                            }    
                    } else {
                        return null;
                    }
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Struktur Ekonomi Berjalan',
                'header' => '<center> Struktur Ekonomi <br> Berjalan </center>',
                'value' => function($model){
                    if($model['id_adjust'] == 'Diskrepansi'){
                    return $model["pdrb_b"]-$model['zpdrb_b'];
                    } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                        return $model['share_b'];
                    }
                }
            ],
            [
                'class' => '\kartik\grid\FormulaColumn',
                'header' => '<center> Adjusted Struktur <br> Ekonomi Berjalan</center>',
                'value' => function ($model, $key, $index, $widget) {
                    $p = compact('model', 'key', 'index');
                    if($model['zpdrb_b'] != 0 || $model['zpdrb_b'] != null ){
                        if($model['id_adjust'] == 'Diskrepansi'){
                            $share_b_adjusted = $widget->col(6,$p)-$model['zpdrb_b'];
                            return $share_b_adjusted ;
                            } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                                $share_b_adjusted = ($widget->col(6,$p)/$model['zpdrb_b'] * 100) ;
                                return $share_b_adjusted;
                            }    
                    } else {
                        return null;
                    }
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Struktur Ekonomi Konstan',
                'header' => '<center> Struktur Ekonomi <br> Konstan </center>',
                'value' => function($model){
                    if($model['id_adjust'] == 'Diskrepansi'){
                    return $model["pdrb_k"]-$model['zpdrb_k'];
                    } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                        return $model['share_k'];
                    }
                }
            ],
            [
                'class' => '\kartik\grid\FormulaColumn',
                'header' => '<center> Adjusted Struktur <br> Ekonomi Konstan</center>',
                'value' => function ($model, $key, $index, $widget) {
                    $p = compact('model', 'key', 'index');
                    if($model['zpdrb_k'] != 0 || $model['zpdrb_k'] != null ){
                        if($model['id_adjust'] == 'Diskrepansi'){
                            $share_k_adjusted = $widget->col(9,$p)-$model['zpdrb_k'];
                            return $share_k_adjusted ;
                            } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                                $share_k_adjusted = ($widget->col(9,$p)/$model['zpdrb_k'] * 100);
                                return $share_k_adjusted;
                            }    
                    } else {
                        return null;
                    }
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Laju Pertumbuhan',
                'header' => '<center> Laju Pertumbuhan <br> (q-to-q) </center>',
                'value' => function($model){
                    if($model['id_adjust'] == 'Diskrepansi'){
                    return $model['pdrb_k'] - $model['pdrb_k_0'];
                    } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                        return $model['laju_p'];
                    }
                }
            ],
            [
                'class' => '\kartik\grid\FormulaColumn',
                'header' => '<center> Adjusted Laju <br> Pertumbuhan (q-to-q) </center>',
                'value' => function ($model, $key, $index, $widget) {
                    $p = compact('model', 'key', 'index');
                    if($model['pdrb_k_0'] != 0 || $model['pdrb_k_0'] != null ){
                        if($model['id_adjust'] == 'Diskrepansi'){
                            $laju_p_adjusted = $widget->col(9, $p) - (float)$model['pdrb_k_0'];
                            return $laju_p_adjusted ;
                            } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                                $laju_p_adjusted = ($widget->col(9, $p)/(float)$model['pdrb_k_0'] *100)-100;
                                return $laju_p_adjusted;
                            }    
                    } else {
                        return null;
                    }
                }
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'label' => 'Laju Pertumbuhan Implisit',
                'header' => '<center> Laju Pertumbuhan <br> Implisit (q-to-q) </center>',
                'value' => function($model){
                    if($model['id_adjust'] == 'Diskrepansi'){
                    return $model['implisit'] - $model['implisit_0'];
                    } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                        return $model['laju_implisit'];
                    }
                }
            ],
            [
                'class' => '\kartik\grid\FormulaColumn',
                'header' => '<center> Adjusted Laju <br> Pertumbuhan Implisit <br> (q-to-q) </center>',
                'value' => function ($model, $key, $index, $widget) use ($total_adhb_selain) {
                    $p = compact('model', 'key', 'index');
                    if($model['implisit_0'] != 0 || $model['implisit_0'] != null ){
                        if($model['id_adjust'] == 'Diskrepansi'){
                            $laju_imp_adjusted = ($widget->col(6, $p)/$widget->col(9, $p)*100) - (float)$model['implisit_0'];
                            return $laju_imp_adjusted ;
                            } else if ($model['id_adjust'] == 'Diskrepansi (%)'){
                                $laju_imp_adjusted = (($widget->col(6, $p)/$widget->col(9, $p) *100)/$model['implisit_0'] *100)-100;
                                return $laju_imp_adjusted;
                            }    
                    } else {
                        return null;
                    }
                }
            ],
        ];
            return $kolom;
    }
    
   
}
