<?php

use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
$asset= app\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <table cellspacing="0" cellpadding="0" border="0" style="width:90%;font-size:12px Verdana,Arial,Helvetica,sans-serif;color:#333">
            <tbody>
                <tr><td><img src="http://hangvip.vn/uploads/mail.png"/></td></tr>
                <tr>
                    <td style="padding:0px;color:#0B3982;">
                        
                        <h4><?php echo $subject ?></h4>                        
                        <div style="border:1px solid #ddd;border-radius:4px;padding:2px;">    
                            <?php $this->beginBody() ?>
                            <?= $content ?>
                            <?php $this->endBody() ?>
                        </div>
                        
                        <div>
                            <p>Your sincerely,</p>
                            <p>Hangvip.vn</p>                           
                            <p>
                               <a href="http://hangvip.vn">Support Online</a> | <a href="http://hangvip.vn">Contact Us</a><br>
                               Please do not reply to this email because we are not monitoring this inbox.<br>
                               Copyright 2013 <a href="http://hangvip.vn">Hangvip.vn</a>. All rights reserved.
                            </p>
                        </div>
                    </td>
                </tr>
                
            </tbody>
        </table>

    </body>
</html>
<?php $this->endPage() ?>
