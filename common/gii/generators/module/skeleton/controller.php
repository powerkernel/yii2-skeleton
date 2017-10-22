<?php
/**
 * This is the template for generating a controller class within a module.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

echo "<?php\n";
?>

/**
 * @author Harry Tang <harry@powerkernel.com>
 * @link https://powerkernel.com
 * @copyright Copyright (c) <?= date('Y') ?> Power Kernel
 */

namespace <?= $generator->getControllerNamespace() ?>;

use yii\web\Controller;

/**
 * Default controller for the `<?= $generator->moduleID ?>` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
