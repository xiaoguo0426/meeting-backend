<?php

namespace app\zoom\controller;

use app\zoom\exception\ZoomException;
use app\zoom\model\MeetingModel;
use app\zoom\util\Zoom;
use think\admin\Controller;
use think\admin\helper\QueryHelper;
use think\admin\service\AdminService;

class Meeting extends Controller
{
    /**
     * Index
     * @auth true
     * @menu true
     */
    public function index()
    {
        MeetingModel::mQuery()->layTable(function () {
            $this->title = 'Meeting';
        }, function (QueryHelper $query) {
        });

    }

    /**
     * Add
     * @auth true
     */
    public function add()
    {
        $this->_applyFormToken();
        MeetingModel::mForm('form');
    }

    protected function _add_form_filter(array &$vo)
    {
        if ($this->app->request->isPost()) {
            try {
                $create = (new Zoom())->createMeeting($vo);
                $vo['meeting_id'] = $create['id'];
                $vo['assistant_id'] = $create['id'];
                $vo['host_email'] = $create['host_email'];
                $vo['join_url'] = $create['join_url'];
                $vo['meeting_status'] = $create['status'];
                $vo['settings'] = json_encode($create['settings']);//直接保存所有的settings
                $vo['created_at'] = date('Y-m-d H:i:s');
                $vo['created_by'] = AdminService::instance()->getUserId();
            } catch (ZoomException $e) {
                //TODO Slack
                p($e->getMessage());
                $this->error('System error, please try again later.');
                return false;
            }
        }
        if ($this->app->request->isGet()) {
            $vo['type'] = '';
            $vo['settings'] = [
                'calendar_type' => '',
                'approval_type' => -1,
                'auto_recording' => ''
            ];
        }
    }

    /**
     * @throws \app\console\exception\ZoomException
     */
    protected function _edit_form_filter(array &$vo)
    {
        if ($this->app->request->isGet()) {
            $vo['settings'] = json_decode($vo['settings'] ?? '{}', true);
        }
        if ($this->app->request->isPost()) {
            //检查变更的选项
            $meeting_id = $vo['meeting_id'];
            unset($vo['meeting_id']);
            try {
                if (! (new Zoom())->updateMeeting($meeting_id, $vo)) {
                    $this->error('System error, please try again later.');
                }
            } catch (ZoomException $exception) {
                p($exception->getMessage());
                $this->error('System error, please try again later.');
            }
            $vo['settings'] = json_encode($vo['settings']);
            $vo['updated_at'] = date('Y-m-d H:i:s');
            $vo['updated_by'] = AdminService::instance()->getUserId();
        }
    }

    /**
     * Edit
     * @auth true
     */
    public function edit()
    {
        $this->_applyFormToken();
        MeetingModel::mForm('form');
    }

    /**
     * Info
     * @auth true
     */
    public function info()
    {
        $this->_applyFormToken();
        MeetingModel::mForm('info');
    }

    public function _form_filter()
    {
        if ($this->app->request->isGet()) {
        }
    }

    /**
     *
     * @param $query  object  查询对象
     * @param $vo    array 查询参数
     * 数
     */
    protected function _state_save_filter(&$query, &$vo)
    {

    }

    /**
     * State
     * @auth true
     */
    public function state()
    {
        MeetingModel::mSave($this->_vali([
            'status.in:0,1' => '状态值范围异常！',
            'status.require' => '状态值不能为空！',
        ]));
    }

    /**
     * Report
     * @auth true
     */
    public function report()
    {
        $this->_applyFormToken();
        //请求ZOOM API 获取报告信息
        $id = input('get.id');
        //        $report = (new Zoom())->reportMeeting($meeting_id);
        $meeting = MeetingModel::mk()->where('id', $id)->find();

        if (empty($meeting)) {
            $this->error('Meeting Not Found!');
        }

        ///会议详细报告 v2/report/meetings/{meetingId}
        $details = '{"custom_keys":[{"key":"Host Nation","value":"US"}],"dept":"HR","duration":2,"end_time":"2022-03-15T07:42:22Z","id":3927350525,"participants_count":2,"start_time":"2022-03-15T07:40:46Z","topic":"My Meeting","total_minutes":3,"tracking_fields":[{"field":"Host Nation","value":"US"}],"type":2,"user_email":"jchill@example.com","user_name":"Jill Chill","uuid":"iOTQZPmhTUq5a232ETb9eg=="}';
        //会议出席人 /v2/past_webinars/{webinarId}/participants
        $attendee = '{"participants":[{"id":"30R7kT7bTIKSNUFEuH_Qlg","name":"Jill Chill","user_email":"jchill@example.com"}]}';
        //获取会议参与者报告 /v2/report/meetings/{meetingId}/participants
        $participants = '{"participants":[{"customer_key":"349589LkJyeW","duration":259,"failover":false,"id":"30R7kT7bTIKSNUFEuH_Qlg","join_time":"2022-03-23T06:58:09Z","leave_time":"2022-03-23T07:02:28Z","name":"example","registrant_id":"example","user_email":"jchill@example.com","user_id":"27423744","status":"in_meeting","bo_mtg_id":"27423744"}]}';
        //获取会议出席者报告

        $this->assign('title', 'Report Detail');
        $this->assign('details', json_decode($details, true));
        $this->assign('attendees', json_decode($attendee, true)['participants']);
        $this->assign('participants', json_decode($participants, true)['participants']);

        $this->fetch('report');
    }


}