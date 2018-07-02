<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\Security;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "m_user".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $kode_daerah
 * @property integer $authKey
 * @property integer $akses
 * @property string $email
 * @property string $telepon
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'm_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'kode_daerah', 'authKey', 'akses', 'kode_val'], 'required'],
            // Kalau error, ubah authKey jadi numeric
            [['id', 'authKey', 'akses', 'kode_val'], 'integer'],
            [['username', 'password', 'email', 'telepon','kode_daerah', 'kode_pdrb'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'kode_daerah' => Yii::t('app', 'Kode Daerah'),
            'authKey' => Yii::t('app', 'Auth Key'),
            'akses' => Yii::t('app', 'Akses'),
            'email' => Yii::t('app', 'Email'),
            'telepon' => Yii::t('app', 'Telepon'),
            'kode_val' => Yii::t('app', 'Kode Validasi'),
            'kode_pdrb' => Yii::t('app', 'Kode PDRB'),
        ];
    }

    /**
     * @inheritdoc
     * @return MUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MUserQuery(get_called_class());
    }
    
    public function getKodeDaerah()
    {
        return $this->hasOne(MDaerah::className(), ['kode_daerah' => 'kode_daerah']);
    }
	
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
	
	 public static function findByUsername($username)
    {
        return static::findOne(['username'=>$username]);
    }
    
    public function validatePassword($password){
    	return Yii::$app->getSecurity()->validatePassword($password, $this->password);;
    }
    
    public function setPassword($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }
    
    public function beforeSave($insert)
    {
        $this->password = $this->setPassword($this->password);
        if(parent::beforeSave($insert)){
            if($this->id == null){
            $this->id = $this->find()->max('id') + 1;
            return true;
            } else {
                return true;
                }
        }else{
            return false;
        }
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return static::findOne(['access_token' => $token]);
    }

}
