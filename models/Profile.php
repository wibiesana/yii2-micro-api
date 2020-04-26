<?php

namespace app\models;

use Yii;
use \app\models\base\Profile as BaseProfile;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "profile".
 */
class Profile extends BaseProfile
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }

    public function fields()
    {
        return [
            // field name is the same as the attribute name
//            'id',
            'name',
            'pic',
            'about',
        ];
    }


    public function getExamResultCount()
    {
        return $this->hasMany(\app\models\ExamResult::className(), ['created_by' => 'user_id'])
            ->where(['>=', 'exam_status_id', ExamResult::STATUS_QUESTION_BANK_FINISH])
            ->count();
    }

    public function getExamGroupUsers()
    {
        $today = date('Y-m-d H:i:s');
        return $this->hasMany(\app\models\ExamGroupUser::className(), ['user_id' => 'user_id'])
            ->with('exam', 'group')
            ->andFilterWhere(['<=', 'start_date', $today])
            ->andFilterWhere(['>=', 'end_date', $today])
            ->limit(4);
    }

    public function getExamGroupUsersCount()
    {
        $today = date('Y-m-d H:i:s');
        return $this->hasMany(\app\models\ExamGroupUser::className(), ['user_id' => 'user_id'])
            ->with('exam', 'group')
            ->andFilterWhere(['<=', 'start_date', $today])
            ->andFilterWhere(['>=', 'end_date', $today])
            ->count();
    }
//
//    public function getGroups()
//    {
//        return $this->hasMany(\app\models\Group::className(), ['created_by' => 'user_id'])
//            ->orderBy(['id' => SORT_DESC]);
//    }
//
//    public function getQuestionBanks()
//    {
//        return $this->hasMany(\app\models\QuestionBank::className(), ['created_by' => 'user_id'])
//            ->orderBy(['id' => SORT_DESC]);
//    }
//
//    public function getExams()
//    {
//        return $this->hasMany(\app\models\Exam::className(), ['created_by' => 'user_id'])
//            ->with('questionBank')
//            ->orderBy(['id' => SORT_DESC]);
//    }
//
//    public function getGroupMembers()
//    {
//        return $this->hasMany(\app\models\GroupMember::className(), ['user_id' => 'user_id'])
//            ->with('group');
//    }
//
//    public function getExamResults()
//    {
//        return $this->hasMany(\app\models\ExamResult::className(), ['created_by' => 'user_id'])
//            ->with('group', 'exam', 'examGiverName')
//            ->orderBy(['id' => SORT_DESC]);
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMyExamCount()
    {
        return $this->hasMany(\app\models\Exam::className(), ['created_by' => 'user_id'])
            ->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupCount()
    {
        return $this->hasMany(\app\models\Group::className(), ['created_by' => 'user_id'])
            ->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionBankCount()
    {
        return $this->hasMany(\app\models\QuestionBank::className(), ['created_by' => 'user_id'])->count();
    }


}
