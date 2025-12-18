<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "items".
 *
 * @property int $id
 * @property int $train_id
 * @property int $station_id
 * @property string $arrive
 * @property string $departure
 *
 * @property Station $station
 * @property Train $train
 */
class Items extends \yii\db\ActiveRecord
{
    public bool $isArrive = false;
    public bool $isDeparture = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['train_id', 'station_id'], 'required'],
            [['arrive', 'departure'], 'safe'],
            [['train_id', 'station_id'], 'integer'],
            [['arrive', 'departure'], 'string', 'max' => 6],
            [['train_id'], 'exist', 'skipOnError' => true, 'targetClass' => Train::class, 'targetAttribute' => ['train_id' => 'id']],
            [['station_id'], 'exist', 'skipOnError' => true, 'targetClass' => Station::class, 'targetAttribute' => ['station_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер поезда',
            'train_id' => 'Поезд',
            'station_id' => 'Станция',
            'arrive' => 'Прибытие',
            'departure' => 'Отправление',
        ];
    }

    /**
     * Gets query for [[Station]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStation()
    {
        return $this->hasOne(Station::class, ['id' => 'station_id']);
    }

    /**
     * Gets query for [[Train]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrain()
    {
        return $this->hasOne(Train::class, ['id' => 'train_id']);
    }

    public function getItems()
    {
        $timezone = new \DateTimeZone('Europe/Moscow');
        $endDateObj = new \DateTime("now", $timezone);
        $endDate = $endDateObj->format('H:i');
        
        return $this->find()
            ->where(['<=', 'arrive', new \yii\db\Expression("NOW()")])
            ->andWhere(['>=','departure', $endDate])
            ->orderBy('arrive')
            ->all();
    }

    public function getFullItems()
    {
        $items = $this->getItems();
        $now = Yii::$app->formatter->asTime('now', 'php:H:i');
        
        foreach ($items as $item) {
            if ($item->arrive) {
                $item->isArrive = Yii::$app->formatter->asTime($item->arrive, 'php:H:i') == $now;
            }
            if ($item->departure) {
                $item->isDeparture = Yii::$app->formatter->asTime($item->departure, 'php:H:i') == $now;
            }
        }

        return $items;
    }

    public function getDepartures()
    {
        $timezone = new \DateTimeZone('Europe/Moscow');
        $endDateObj = new \DateTime("now", $timezone);
        $endDate = $endDateObj->format('H:i');
        
        return $this->find()
            ->where(['is', 'arrive', new \yii\db\Expression('null')])
            ->andWhere(['>=','departure', $endDate])
            ->orderBy('departure')
            ->limit(5)
            ->all();
    }

    public function getArrives()
    {
        $timezone = new \DateTimeZone('Europe/Moscow');
        $endDateObj = new \DateTime("now", $timezone);
        $endDate = $endDateObj->format('H:i');
        
        return $this->find()
            ->where(['is', 'departure', new \yii\db\Expression('null')])
            ->andWhere(['>=','arrive', $endDate])
            ->orderBy('arrive')
            ->limit(5)
            ->all();
    }

    public function search($params)
    {
       $data = [];

       if($params['Items']['station']) {
            $station = Station::findOne([
                'name' => $params['Items']['station'],
            ]);
            if ($station) {
                var_dump($station->id);
                $data = $station->items;
            }
       }

       return $data;

    }

}
