<?php declare(strict_types = 1);

namespace PHPStan\Rules\Variables;

use PHPStan\Rules\Rule;

class CompatibleVariableTypeRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): Rule
	{
		return new CompatibleVariableTypeRule();
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/compatible-variable-type.php'], [
			[
				'Variable $int (string) can\'t change type (int) after declaration.',
				6,
			],
			[
				'Variable $int (int) can\'t change type (string) after declaration.',
				7,
			],
		]);
	}

}
