<?php declare(strict_types = 1);

namespace PHPStan\Rules\Variables;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type\ConstantType;
use PHPStan\Type\ErrorType;
use PHPStan\Type\NullType;
use PHPStan\Type\VerbosityLevel;

class CompatibleVariableTypeRule implements Rule
{

	public function getNodeType(): string
	{
		return Assign::class;
	}

	/**
	 * @param \PhpParser\Node\Expr\Assign $node
	 * @param \PHPStan\Analyser\Scope $scope
	 * @return string[] errors
	 */
	public function processNode(Node $node, Scope $scope): array
	{
		$variable = $node->var;
		if (!$variable instanceof Variable) {
			return [];
		}

		$variableName = $variable->name;
		if (!is_string($variableName)) {
			return [];
		}

		$variableType = $scope->getType($variable);
		if ($variableType instanceof ErrorType) {
			return [];
		}

		if ($variableType instanceof ConstantType) {
			$variableType = $variableType->generalize();
		}

		$expressionType = $scope->getType($node->expr);
		if ($expressionType instanceof ConstantType) {
			$expressionType = $expressionType->generalize();
		}

		if (
			$variableType instanceof NullType
			|| $expressionType instanceof NullType
		) {
			return [];
		}

		if (
			!$variableType->accepts($expressionType, true)->no()
			|| !$expressionType->accepts($variableType, true)->no()
		) {
			return [];
		}

		return [sprintf(
			'Variable $%s (%s) can\'t change type (%s) after declaration.',
			$variableName,
			$expressionType->describe(VerbosityLevel::typeOnly()),
			$variableType->describe(VerbosityLevel::typeOnly())
		)];
	}

}
