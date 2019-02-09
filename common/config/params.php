<?php
return [
    'rootAdmin'=>[1],
    'gitHubPage'=>null, // overwrite on params-local.php with https://powerkernel.github.io/domain.com
    'sitemapPageSize'=>200,
    'enableBlog'=>true,
    'showPowered'=>true,
    'demo_account'=>'',
    'demo_pass'=>'',
    'themes'=>[],
	'organization' => [
			'legalName' => 'Power Kernel Inc',
			'address' => '', // String
			'phone'=>'',
			'social' => [
				'google' => [
					'url',
					'icon',
				],
				'facebook' => [
					'url',
					'icon',
				],
				// ...
			],
			'contactEmail'=>'group-email@domain.com',
			'adminEmail'=>'admin-email@domain.com',
			'email_brand_logo'=>'https://powerkernel.github.io/DOMAIN/icons/email_brand.png'
		],	
];
