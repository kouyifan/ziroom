<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/3/21
 * Time: 23:49
 */
namespace Modules\Ziroom\Services;
use mikehaertl\shellcommand\Command;

class PythonServices{

    private $shell_command;

    public function __construct()
    {
        $this->shell_command = new Command();
    }

    public function _get_image_font($img_src = ''){
        $path = base_path().'/scripts/python/get_ziroom_price.py';
        $command = $this->shell_command->setCommand("python3.4 $path $img_src");

        if ($command->execute()) {
            return $command->getOutput();
        } else {
//            echo $command->getError();
//            $exitCode = $command->getExitCode();
            return '';
        }
    }

}