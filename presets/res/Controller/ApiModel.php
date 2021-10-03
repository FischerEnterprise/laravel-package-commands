<?php
$modelPath = "$modelNamespace$modelName";
$modelVar = '$' . lcfirst($modelName);
?>
<?='<?php'?>


namespace <?=$namespace?>;

use <?=$modelPath?>;
use Illuminate\Http\Request;

class <?=$className?> extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \<?=$modelPath?>  <?=$modelVar?>

     * @return \Illuminate\Http\Response
     */
    public function show(<?=$modelName?> <?=$modelVar?>)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \<?=$modelPath?>  <?=$modelVar?>

     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, <?=$modelName?> <?=$modelVar?>)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \<?=$modelPath?>  <?=$modelVar?>

     * @return \Illuminate\Http\Response
     */
    public function destroy(<?=$modelName?> <?=$modelVar?>)
    {
        //
    }
}
