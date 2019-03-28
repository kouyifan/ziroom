<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/3/21
 * Time: 15:19
 * 抓取自如数据的类
 */
namespace Modules\Ziroom\Repositories\Contracts;
interface GrabZiroomInterface{
    //查询增加自如区域数据
    public function findZiroomAreaData();
    //查询增加自如地铁数据
    public function findZiroomSubwayData();
    //查询自如区域分类数据
    public function findZiroomAreaOrSubwayDbData($class,$type,$id);
    //获取分页列表
    public function getPageByUrl($url1 = '',$url2 = '');
    //获取列表list数据
    public function getListDataByPage($url);
    //获取详情页数据
    public function getZiroomDetails($url = '');
}