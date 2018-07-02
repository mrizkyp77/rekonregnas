<?php

namespace app\models;

use Yii;
use app\models\Kbli;


class PdrbProv extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pdrb_prov';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {       //SESUAIKAN RULE DI DB : PK, FK, TIPE DATA
        return [
            [['id_prov', 'id_pdrb','tahun_dasar','tahun', 'triwulan', 'putaran' , 'revisi', 'timestamp'], 'required'],
            [['id_prov', 'id_pdrb', 'status', 'timestamp'], 'string'],
            [['flag'], 'default', 'value' => 0],
            [['tahun_dasar','tahun', 'triwulan', 'putaran', 'revisi'], 'integer'],
            [['pdrb_b', 'pdrb_k', 'share_b', 'share_k','laju_p_q', 'laju_p_y', 'laju_p_c', 'implisit', 'laju_imp_q', 'laju_imp_c', 'laju_imp_y', 'diskrepansi_b','diskrepansi_k','flag'], 'number'],
            [['id_prov', 'id_pdrb', 'tahun_dasar','tahun', 'triwulan', 'putaran', 'revisi'], 'unique', 'targetAttribute' => ['id_prov', 'id_pdrb','tahun_dasar','tahun', 'triwulan', 'putaran', 'revisi', 'timestamp',]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_prov' => Yii::t('app', 'Id Prov'),
            'id_pdrb' => Yii::t('app', 'Id Pdrb'),
            'tahun_dasar' => Yii::t('app', 'Tahun Dasar'),
            'tahun' => Yii::t('app', 'Tahun'),
            'triwulan' => Yii::t('app', 'Triwulan'),
            'putaran' => Yii::t('app', 'Putaran'),
            'revisi' => Yii::t('app', 'Revisi'),
            'pdrb_b' => Yii::t('app', 'Pdrb B'),
            'pdrb_k' => Yii::t('app', 'Pdrb K'),
            'share_b' => Yii::t('app', 'Share B'),
            'share_k' => Yii::t('app', 'Share K'),
            'laju_p_q' => Yii::t('app', 'Laju Pertumbuhan q-to-q'),
            'laju_p_y' => Yii::t('app', 'Laju Pertumbuhan y-to-y'),
            'laju_p_c' => Yii::t('app', 'Laju Pertumbuhan c-to-c'),
            'implisit' => Yii::t('app', 'Implisit'),
            'laju_imp_q' => Yii::t('app', 'Laju Implisit q-to-q'),
            'laju_imp_y' => Yii::t('app', 'Laju Implisit y-to-y'),
            'laju_imp_c' => Yii::t('app', 'Laju Implisit c-to-c'),
            'diskrepansi_b' => Yii::t('app', 'Diskrepansi Berjalan'),
            'diskrepansi_k' => Yii::t('app', 'Diskrepansi Konstan'),
            'flag' => Yii::t('app', 'Flag'),
            'status' => Yii::t('app', 'Status'),
            'timestamp' => Yii::t('app', 'Timestamp'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PdrbProvQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PdrbProvQuery(get_called_class());
    }
    
    public static function getNamaPdrb($id)
    {   
        $model = Kbli::find()->where(["kode_pdrb" => $id])->one();
            if(!empty($model)){
                return $model->ket_pdrb;
            }

            return null;
    }
    
    public function getAdhbByTime($prov, $pdrb, $waktu){
        $adhb = PdrbProv::find()->select('pdrb_b')->where(['id_prov' => $prov, 'id_pdrb' => $pdrb, 'timestamp' => $waktu])->one();
        return $adhb;
    }
    
    public function getAdhkByTime($prov, $pdrb, $waktu){
        $adhk = PdrbProv::find()->select('pdrb_k')->where(['id_prov' => $prov, 'id_pdrb' => $pdrb, 'timestamp' => $waktu])->one();
        return $adhk;
    }
    public function getPdbByTime($tahun, $triwulan,$pdrb, $ref_pdb){
        $pdb = PdrbProv::find()->select('pdrb_b', 'pdrb_k')->where(['id_prov' => "0100", 'id_pdrb' => $pdrb, 'timestamp' => $ref_pdb])->one();
        return $pdb;
    }
    
    public function getIndImpByTime($prov, $pdrb, $waktu){
        $indImp = PdrbProv::find()->select('implisit')->where(['id_prov' => $prov, 'id_pdrb' => $pdrb, 'timestamp' => $waktu])->one();
        return  $indImp;     
    }
    //Untuk mendapatkan referensi datetime data terbaru milik suatu provinsi untuk tahun dan triwulan spesifik
    public function getReferensiDataTerbaru($prov, $tahun, $triwulan){
        if (PdrbProv::find()->where(['id_prov' => $prov, 'tahun'=> $tahun, 'triwulan'=>$triwulan])->exists()){
            $ref = PdrbProv::find()->select('timestamp')->where(['id_prov' => $prov, 'tahun' => $tahun, 'triwulan' => $triwulan])->max('timestamp');
            return $ref;
        } else {
            return null;
        }
    }    

    // Untuk mendapat referensi data terbaru untuk perhitungan nilai turunan perbandingan triwulan (q-to-q)
    public function getReferensiQ($prov, $tahun, $triwulan){
        if ($triwulan > 1){
            $ref_q = PdrbProv::getReferensiDataTerbaru($prov, $tahun, $triwulan-1);
            return $ref_q;
        }
        else {
            $ref_q = PdrbProv::getReferensiDataTerbaru($prov, $tahun-1, 4);
            return $ref_q;
        }
    }
    
    // Untuk mendapat referensi data terbaru untuk perhitungan nilai turunan perbandingan kumulatif (c-to-c) untuk tahun tersebut
    public function getReferensiC($prov, $tahun, $triwulan){
        $array = [];
        $i = 0;
        for( $counter = $triwulan; $counter > 0; $counter--){
            $array[$i] = PdrbProv::getReferensiQ($prov, $tahun, $triwulan);
            $i++;
        }
        return $array;
    }
    
    // Untuk mendapat referensi data terbaru untuk perhitungan nilai turunan perbandingan tahunan (y-to-y)
    public function getReferensiY($prov, $tahun, $triwulan){
        if(PdrbProv::find()->where(['id_prov' => $prov, 'tahun' => $tahun-1, 'triwulan' => $triwulan])->exists()){
        $ref = PdrbProv::getReferensiDataTerbaru($prov, $tahun-1, $triwulan);
        return $ref;
        }
        else {
            return null;
        }
    } 
    
    public function getReferensiPdb($tahun, $triwulan){
        if(PdrbProv::find()->where(['id_prov' => "0100", 'tahun' => $tahun, 'triwulan' => $triwulan])->exists()){
            $ref = PdrbProv::getReferensiDataTerbaru("0100", $tahun, $triwulan);
            return $ref;
        }
        else {
            return null;
        }
    }
    
    //Untuk mendapat nilai distribusi persentase produk atas dasar harga berlaku per kategori
    //Nilai PDRB dan ADHB kategori dari hasil query total $data[no_array][variabel]
    public static function getDistPerB($PDRB_b, $adhb){
        if($PDRB_b != 0){
            (float)$distPerB = (float)$adhb/(float)$PDRB_b *100;
            return $distPerB;
       }
        else {
           return 0;
       }
    }
    
    public static function getDistPerK($PDRB_k, $adhk){
        if($PDRB_k != 0){
            (float)$distPerK = (float)$adhk/(float)$PDRB_k *100;
            return $distPerK;
        }
        else {
            return 0;
        }
    }
    
    //Untuk mendapat struktur ekonomi
    
    //Untuk mendapat laju pertumbuhan berdasarkan referensi waktu (q-to-q, c-to-c, atau y-to-y)
    public static function getLajuPQ($prov, $pdrb, $ref_q, $pdrb_k){
        $pdrb_k_0 = PdrbProv::getAdhkByTime($prov, $pdrb, $ref_q);
        if($pdrb_k_0->pdrb_k != 0){
            $lajuPQ = ($pdrb_k / $pdrb_k_0->pdrb_k *100) - 100;
            return $lajuPQ;
        }
        else {
            return 0;
        }
    }
    
    //Kumulatif triwulan tahun ini terhadap kumulatif triwulan tahun selanjutnya
    public static function getLajuPC($prov, $pdrb, $ref_c, $ref_c_0, $pdrb_k, $triwulan){
        (float)$pdrb_k_c = (float)$pdrb_k;
        (float)$pdrb_k_c_0 = null;
        if ($ref_c == null){
            return null;
        } else {
        if ($triwulan > 1){
            foreach($ref_c as $waktu){
                $adhk = PdrbProv::getAdhkByTime($prov, $pdrb, $waktu);
                $pdrb_k_c = $pdrb_k_c + $adhk->pdrb_k;
            }
            foreach($ref_c_0 as $waktu_0){
                $adhk_0 = PdrbProv::getAdhkByTime($prov, $pdrb, $waktu_0);
                $pdrb_k_c_0 = $pdrb_k_c_0 + $adhk_0->pdrb_k;
            }
        }
        else {
            $pdrb_k_0 = PdrbProv::getAdhkByTime($prov, $pdrb, $ref_c_0[0]);
            $pdrb_k_c_0 = $pdrb_k_0->pdrb_k;
        }
            if ($pdrb_k_c_0 == 0){
                return 0;
            }
                (float)$lajuPC = ($pdrb_k_c/$pdrb_k_c_0 *100) - 100;
                return $lajuPC;
        }
    }        
    
    //Untuk y-to-y masih belum yakin apakah nilainya sebenarnya antara triwulan beda tahun atau kumulatif antar tahun
    //Triwulan antar tahun Sudah Benar
    public static function getLajuPY($prov, $pdrb,$ref_y, $pdrb_k){
        $pdrb_k_0 = PdrbProv::getAdhkByTime($prov, $pdrb, $ref_y);
        if($pdrb_k == null){
            return null;
        }
        else {
        if($pdrb_k_0->pdrb_k != 0 ){
            (float)$lajuPY = ($pdrb_k / $pdrb_k_0->pdrb_k *100) - 100;
            return $lajuPY;
        }
        else {
            return 0;
        }
        }
    }
 
    //Untuk mendapat indeks implisit
    public static function getIndImp($adhb, $adhk){
        if($adhk != 0){
            (float)$indImp = (float)$adhb/(float)$adhk *100;
            return $indImp;
        } 
        else {
            return 0;    
        }
    }
    
    //Untuk mendapat laju implisit
    // Q-to-Q
    public static function getLajuImpQ($prov, $pdrb, $ref_q, $ind_imp){
        $ind_imp_0 = PdrbProv::getIndImpByTime($prov, $pdrb, $ref_q);
        if ($ind_imp_0 == null){
            $ind_imp_0 = PdrbProv::getIndImp(PdrbProv::getAdhbByTime($prov, $pdrb, $ref_q), PdrbProv::getAdhkByTime($prov, $pdrb, $ref_q));
        }
        if($ind_imp_0->implisit != 0){
            $lajuImpQ = ($ind_imp/$ind_imp_0->implisit *100) -100;
            return $lajuImpQ;
        }
        else {
            return 0;
        }
    }
    
    //C-to-C 
    public static function getLajuImpC($prov, $pdrb, $ref_c, $ref_c_0, $indImp, $triwulan){
        $ind_imp_c_0 = null;
        $ind_imp_c = $indImp;
        
        if($ref_c_0 == null){
            return null;
        } else {
            foreach($ref_c_0 as $waktu_0){
                $ind_imp_0 = PdrbProv::getIndImpByTime($prov, $pdrb, $waktu_0);
                if ($ind_imp_0 == null){
                    return null;
                    }
                $ind_imp_c_0 = $ind_imp_c_0 + $ind_imp_0->implisit;
            }
            foreach($ref_c as $waktu){
                $ind_imp = PdrbProv::getIndImpByTime($prov, $pdrb, $waktu);
                if ($ind_imp == null){
                    $ind_imp = PdrbProv::getIndImp(PdrbProv::getAdhbByTime($prov, $pdrb, $waktu), PdrbProv::getAdhkByTime($prov, $pdrb, $waktu));
                }
                $ind_imp_c = $ind_imp_c + $ind_imp->implisit;
            }
        
        if($ind_imp_c_0 == 0){
            return 0;
        }
            $lajuImpC = ($ind_imp_c/$ind_imp_c_0 *100) - 100;
            return $lajuImpC;
        }
    }   
    
    //Y-to-Y
    public static function getLajuImpY($prov, $pdrb, $ref_y, $ind_imp){
        $ind_imp_y_0 = PdrbProv::getIndImpByTime($prov, $pdrb, $ref_y);
        if ($ind_imp_y_0 == null){
            $ind_imp_y_0 = $ind_imp_0 = PdrbProv::getIndImp(PdrbProv::getAdhbByTime($prov, $pdrb, $ref_y), PdrbProv::getAdhkByTime($prov, $pdrb, $ref_y));
        }
        if($ind_imp_y_0->implisit != 0 ){
            $lajuImpY = ($ind_imp/$ind_imp_y_0->implisit *100) - 100;
            return $lajuImpY;
        }
        else {
            return 0;
        }
    }
    
    public static function getTotalADHBProv($id_pdrb, $tahun, $triwulan){
        (float)$total_adhb = 0;
        $list_prov = Daerah::find()->select('kode_daerah')->where(['!=', 'kode_daerah', '0000'])->andWhere(['!=', 'kode_daerah', '0100'])->andWhere(['kode_kab'=>'00'])->all();
//        echo var_dump($list_prov);
        foreach ($list_prov as $id_prov){
            if(PdrbProv::find()->where(['id_prov'=>$id_prov->kode_daerah, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan])->exists()){
                $ref_waktu = PdrbProv::find()->where(['id_prov'=>$id_prov, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan])->max('timestamp');
                $adhb = PdrbProv::find()->select('pdrb_b')->where(['id_prov'=>$id_prov, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan, 'timestamp'=>$ref_waktu])->one();
                $total_adhb = $total_adhb + (float)$adhb->pdrb_b;
            } else {
            }
        }
        return $total_adhb;
    }
    
    public static function getTotalADHKProv($id_pdrb, $tahun, $triwulan){
        (float)$total_adhk = 0;
        $list_prov = Daerah::find()->select('kode_daerah')->where(['!=', 'kode_daerah', '0000'])->andWhere(['!=', 'kode_daerah', '0100'])->andWhere(['kode_kab'=>'00'])->all();
        foreach ($list_prov as $id_prov){
            if(PdrbProv::find()->where(['id_prov'=>$id_prov->kode_daerah, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan])->exists()){
                $ref_waktu = PdrbProv::find()->where(['id_prov'=>$id_prov, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan])->max('timestamp');
                $adhk= PdrbProv::find()->select('pdrb_k')->where(['id_prov'=>$id_prov, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan, 'timestamp'=>$ref_waktu])->one();
                $total_adhk = $total_adhk + (float)$adhk->pdrb_k;
            } else {
            }
        }
        return $total_adhk;
    }
    
    public static function getTotalADHBProvSelain($id_prov, $id_pdrb, $tahun, $triwulan){
        (float)$total_adhb = 0;
        $list_prov = Daerah::find()->select('kode_daerah')->where(['!=', 'kode_daerah', '0000'])->andWhere(['!=', 'kode_daerah', '0100'])->andWhere(['!=', 'kode_daerah', $id_prov])->andWhere(['kode_kab'=>'00'])->all();
        if($list_prov){
        foreach ($list_prov as $id_prov){
            if(PdrbProv::find()->where(['id_prov'=>$id_prov->kode_daerah, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan])->exists()){
                $ref_waktu = PdrbProv::find()->where(['id_prov'=>$id_prov, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan])->max('timestamp');
                $adhb = PdrbProv::find()->select('pdrb_b')->where(['id_prov'=>$id_prov, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan, 'timestamp'=>$ref_waktu])->one();
                $total_adhb = $total_adhb + (float)$adhb->pdrb_b;
            } else {
            }
        }
        }
        return $total_adhb;
    }
    
    public static function getTotalADHKProvSelain($id_prov, $id_pdrb, $tahun, $triwulan){
        (float)$total_adhk = 0;
        $list_prov = Daerah::find()->select('kode_daerah')->where(['!=', 'kode_daerah', '0000'])->andWhere(['!=', 'kode_daerah', '0100'])->andWhere(['!=', 'kode_daerah', $id_prov])->andWhere(['kode_kab'=>'00'])->all();
        if($list_prov){
        foreach ($list_prov as $id_prov){
            if(PdrbProv::find()->where(['id_prov'=>$id_prov->kode_daerah, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan])->exists()){
                $ref_waktu = PdrbProv::find()->where(['id_prov'=>$id_prov, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan])->max('timestamp');
                $adhk= PdrbProv::find()->select('pdrb_k')->where(['id_prov'=>$id_prov, 'id_pdrb'=>$id_pdrb, 'tahun'=>$tahun, 'triwulan'=>$triwulan, 'timestamp'=>$ref_waktu])->one();
                $total_adhk = $total_adhk + (float)$adhk->pdrb_k;
            } else {
            }
        }
        }
        return $total_adhk;
    }
}