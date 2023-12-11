<?php

use App\Models\PlanType;

return [
	'free' => [
		'plan_id'=> PlanType::FREE,
		'pricing' => 0,
		'subscribers' => 2500,
		'team_members' => 1,
		'domains' => 1,
		'emails_limit' => 200,
		'workspaces' => 1,
		'features' => [
			'Email Support',
			'No Custom Logo',
			'Basic Integrations'
		]
	],
	'pro' => [
		'plan_id'=> PlanType::PRO,
		'pricing' => 25,
		'subscribers' => 5000,
		'team_members' => 5,
		'domains' => 2,
		'emails_limit' => null,
		'workspaces' => 2,
		'features' => [
			'Custom Logo',
			'Chat &Email Support',
			'Advanced Integrations'
		]
	],
	'premium' => [
		'plan_id'=> PlanType::PREMIUM,
		'pricing' => 50,
		'subscribers' => 10000,
		'team_members' => 10,
		'domains' => 5,
		'emails_limit' => null,
		'workspaces' => 5,
		'features' => [
			'Custom Logo',
			'Chat &Email Support',
			'Full Integrations'
		]
	]
];