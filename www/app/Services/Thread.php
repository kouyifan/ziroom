<?php
namespace App\Services;

class Thread extends \Thread{

    private $value;

    public function __construct(int $i)
    {
        $this->value = $i;
    }

    public function run()
    {
        $s=0;

        for ($i=0; $i<10000; $i++)
        {
            $s++;
        }

        echo "Task: {$this->value}\n";
    }

}
$pool = new Pool(4);

for ($i = 0; $i < 15000; ++$i)
{
    $pool->submit(new Task($i));
}

while ($pool->collect());

$pool->shutdown();