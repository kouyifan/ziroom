<?php
/**
 * Created by PhpStorm.
 * User: 技术主管寇一凡
 * Date: 2019/3/30
 * Time: 21:59
 */
namespace Modules\Ziroom\Observers;

use Modules\Ziroom\Entities\Asset;
use Modules\Ziroom\Services\FileSystemService;

class AssetObserver
{
    /**
     * 监听创建用户事件.
     *
     * @param
     * @return void
     */
    public function created(Asset $asset)
    {
        $fileService = new FileSystemService();

        $asset->file_size = $fileService->_get_file_size($asset->file_path,$asset->disk);
        $asset->file_hash = $fileService->_get_file_hash($asset->file_path,$asset->disk);
        $asset->file_suffix = fn_get_file_ext($asset->file_path);
        $asset->file_type = strtoupper($fileService->_get_file_mime($asset->file_path,$asset->disk));

        $asset->save();
    }


}
