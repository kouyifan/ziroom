<?php

namespace Modules\Ziroom\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Modules\Ziroom\Repositories\Contracts\GrabZiroomInterface;

class GrabListCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'grab:list {listType?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grab Ziroom Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(GrabZiroomInterface $grabZiroom)
    {
        $listType = $this->argument('listType');

        if (empty($listType)) {
            return $this->error('listType Must!');
        }
        switch ($listType){
            case 'list1':
                $grabZiroom->handleZiroomList(config('ziroom.Grab_Urls.list1'),config('ziroom.Grab_Urls.list1_page'),'0');
                break;
            case 'list2':
                $grabZiroom->handleZiroomList(config('ziroom.Grab_Urls.list2'),config('ziroom.Grab_Urls.list2_page'),'1');
                break;
            case 'list3':
                $grabZiroom->handleZiroomList(config('ziroom.Grab_Urls.list3'),config('ziroom.Grab_Urls.list3_page'),'2');
                break;
            default:

                break;
        }

        $this->info($listType . ' success!');
        //
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
//        return [
//            ['example', InputArgument::REQUIRED, 'An example argument.'],
//        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
//        return [
//            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
//        ];
    }
}
