<?php

namespace CheckDeprecatedStaticMethodCall;

Foo::foo();
Foo::deprecatedFoo();

Bar::deprecatedFoo();
Bar::deprecatedFoo2();

DeprecatedBar::foo();
DeprecatedBar::deprecatedFoo();
DeprecatedBar::deprecatedFoo2();

class Bar2 extends Foo
{

	public static function deprecatedFoo()
	{
		parent::foo();
		parent::deprecatedFoo();
	}

}
