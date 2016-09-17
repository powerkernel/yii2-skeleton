<?php

use backend\controllers\SettingController;
use yii\db\Migration;

/**
 * Class m160910_120958_create_page
 */
class m160910_120958_create_page extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%core_page_id}}', [
            'id' => $this->string(100)->notNull(),
            'show_description' => $this->boolean()->notNull()->defaultValue(1),
            'show_update_date' => $this->boolean()->notNull()->defaultValue(1),
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%core_page_id}}', 'id');

        $this->createTable('{{%core_page_data}}', [
            'id_page' => $this->string(100)->notNull(),
            'language' => $this->string(5)->notNull(),

            'title' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'keywords' => $this->string()->notNull(),
            'thumbnail'=> $this->string(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('pk', '{{%core_page_data}}', ['id_page', 'language']);
        $this->addForeignKey('fk_page_data_id_page-page_id_id', '{{%core_page_data}}', 'id_page', '{{%core_page_id}}', 'id', 'CASCADE', 'CASCADE');

        $this->addForeignKey('fk_page_data_created_by-account_id', '{{%core_page_data}}', 'created_by', '{{%core_account}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_page_data_updated_by-account_id', '{{%core_page_data}}', 'updated_by', '{{%core_account}}', 'id', 'CASCADE', 'CASCADE');

        $this->addDefaultPage();
        SettingController::updateSetting();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_page_data_created_by-account_id', '{{%core_page_data}}');
        $this->dropForeignKey('fk_page_data_updated_by-account_id', '{{%core_page_data}}');
        $this->dropForeignKey('fk_page_data_id_page-page_id_id', '{{%core_page_data}}');
        $this->dropTable('{{%core_page_data}}');
        $this->dropTable('{{%core_page_id}}');
    }

    /**
     * add default pages
     */
    protected function addDefaultPage(){
        /* terms */
        $terms=new \common\models\Page();
        $terms->id='terms';
        $terms->show_description=0;
        $terms->show_update_date=1;
        if($terms->save()){
            $data=new \common\models\PageData();
            $data->id_page=$terms->id;
            $data->language=Yii::$app->language;
            $data->title='Terms of Use';
            $data->description='If you continue to use this website, you certify that you have read and agree to the following terms.';
            $data->content=<<<EOB
<p>Welcome to {APP_DOMAIN}. If you continue to browse and use this website, you are agreeing to comply with and be bound by the following terms and conditions of use, which together with our privacy policy govern {APP_NAME}'s relationship with you in relation to this website. If you disagree with any part of these terms and conditions, please do not use our website.</p>
<p>The term '{APP_NAME}' or 'us' or 'we' refers to the owner of the website. The term 'you' refers to the user or viewer of our website.</p>
<p><img class="img-thumbnail" title="Terms of Use" src="https://c1.staticflickr.com/9/8106/29359142860_fe31dc06a1_o.png" alt="Terms of Use" width="1200" height="630" /></p>
<p>The use of this website is subject to the following terms of use:</p>
<ul>
<li>The content of the pages of this website is for your general information and use only. It is subject to change without notice.</li>
<li>This website uses cookies to monitor browsing preferences.</li>
<li>Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.</li>
<li>Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</li>
<li>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.</li>
<li>All trade marks reproduced in this website which are not the property of, or licensed to, the operator are acknowledged on the website.</li>
<li>Unauthorised use of this website may give rise to a claim for damages and/or be a criminal offence.</li>
<li>From time to time this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).</li>
<li>Your use of this website and any dispute arising out of such use of the website is subject to the laws of Vietnam.</li>
</ul>
EOB;
            $data->keywords='terms of use, conditions';
            $data->thumbnail='https://c1.staticflickr.com/9/8106/29359142860_fe31dc06a1_o.png';
            $data->save();
        }

        /* privacy */
        $privacy=new \common\models\Page();
        $privacy->id='privacy';
        $privacy->show_description=0;
        $privacy->show_update_date=1;
        if($privacy->save()){
            $data=new \common\models\PageData();
            $data->id_page=$privacy->id;
            $data->language=Yii::$app->language;
            $data->title='Privacy Policy';
            $data->description='This privacy policy applies solely to information collected by our website.';
            $data->content=<<<EOB
<p>This privacy policy sets out how {APP_NAME} uses and protects any information that you give {APP_NAME} when you use this website.</p>
<p><img class="img-thumbnail" title="Privacy Policy" src="https://c1.staticflickr.com/9/8393/29359142920_7f14649ce8_o.png" alt="Privacy Policy" width="1200" height="630" /></p>
<p>{APP_NAME} is committed to ensuring that your privacy is protected. Should we ask you to provide certain information by which you can be identified when using this website, then you can be assured that it will only be used in accordance with this privacy statement.<br />{APP_NAME} may change this policy from time to time by updating this page. You should check this page from time to time to ensure that you are happy with any changes.<br /><br /><strong>What we collect</strong> <br />We may collect the following information:</p>
<ul>
<li>name and job title</li>
<li>contact information including email address</li>
<li>demographic information such as postcode, preferences and interests</li>
<li>other information relevant to customer surveys and/or offers</li>
</ul>
<p><strong>What we do with the information we gather</strong><br />We require this information to understand your needs and provide you with a better service, and in particular for the following reasons:</p>
<ul>
<li>Internal record keeping.</li>
<li>We may use the information to improve our products and services.</li>
<li>We may periodically send promotional email about new products, special offers or other information which we think you may find interesting using the email address which you have provided.</li>
<li>From time to time, we may also use your information to contact you for market research purposes. We may contact you by email, phone, fax or mail.</li>
<li>We may use the information to customize the website according to your interests.</li>
<li>We may provide your information to our third party partners for marketing or promotional purposes.</li>
<li>We will never sell your information.</li>
</ul>
<p><strong>Security</strong> <br />We are committed to ensuring that your information is secure. In order to prevent unauthorized access or disclosure we have put in place suitable physical, electronic and managerial procedures to safeguard and secure the information we collect online.</p>
<p><strong>How we use cookies</strong> <br />A cookie is a small file which asks permission to be placed on your computer's hard drive. Once you agree, the file is added and the cookie helps analyze web traffic or lets you know when you visit a particular site. Cookies allow web applications to respond to you as an individual. The web application can tailor its operations to your needs, likes and dislikes by gathering and remembering information about your preferences. <br /><br />We use traffic log cookies to identify which pages are being used. This helps us analyze data about web page traffic and improve our website in order to tailor it to customer needs. We only use this information for statistical analysis purposes and then the data is removed from the system. <br /><br />Overall, cookies help us provide you with a better website, by enabling us to monitor which pages you find useful and which you do not. A cookie in no way gives us access to your computer or any information about you, other than the data you choose to share with us. <br /><br />You can choose to accept or decline cookies. Most web browsers automatically accept cookies, but you can usually modify your browser setting to decline cookies if you prefer. This may prevent you from taking full advantage of the website.<br /><br /><strong>Links to other websites</strong><br />Our website may contain links to enable you to visit other websites of interest easily. However, once you have used these links to leave our site, you should note that we do not have any control over that other website. Therefore, we cannot be responsible for the protection and privacy of any information which you provide whilst visiting such sites and such sites are not governed by this privacy statement. You should exercise caution and look at the privacy statement applicable to the website in question.</p>
<p><strong>Controlling your personal information</strong><br />You may choose to restrict the collection or use of your personal information in the following ways:</p>
<ul>
<li>whenever you are asked to fill in a form on the website, look for the box that you can click to indicate that you do not want the information to be used by anybody for direct marketing purposes</li>
<li>if you have previously agreed to us using your personal information for direct marketing purposes, you may change your mind at any time by writing to or emailing us</li>
</ul>
<p>We will not sell, distribute or lease your personal information to third parties unless we have your permission or are required by law. We may use your personal information to send you promotional information about third parties which we think you may find interesting if you tell us that you wish this to happen.<br /><br />If you believe that any information we are holding on you is incorrect or incomplete, please write to or email us as soon as possible. We will promptly correct any information found to be incorrect.</p>
EOB;
            $data->keywords='privacy, policy';
            $data->thumbnail='https://c1.staticflickr.com/9/8393/29359142920_7f14649ce8_o.png';
            $data->save();
        }
    }
}
