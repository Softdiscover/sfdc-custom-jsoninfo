<?php
declare(strict_types=1);
/**
 *
 * @category logicCore
 * @author   Raimundo Yabar <djyabar@gmail.com>
 * @license  [GPLv2+] <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     example.com <https://example.com>
 */
use sfdc\wpJsonPlaceholder\core\LogicCore;
use sfdc\wpJsonPlaceholder\Tests\TestCase;


use Brain\Monkey;
use Brain\Monkey\Functions;
use Brain\Monkey\Actions;
use Brain\Monkey\Filters;

/**
 * Class logicCore
 *
 * @category logicCore
 * @author   Raimundo Yabar <djyabar@gmail.com>
 * @license  [GPLv2+] <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     example.com <https://example.com>
 */
class LogicCore_Test extends TestCase {
	use \sfdc\wpJsonPlaceholder\core\Helper;
	/**
	 * @var LogicCore
	 */
	private $instance;

	/**
	 * This function will be executed before each test.
	 */
	public function setUp(): void {
		parent::setUp();

		$this->instance = Mockery::mock( LogicCore::class )
			->shouldAllowMockingProtectedMethods()
			->makePartial();
	}

	public function test_changeTitle() {
		$this->instance->expects( 'validateSlug' )
		->once()
		->andReturn( true );

		$expected = 'Showing Users';
		$actual   = $this->instance->changeTitle( '' );
		$this->assertEquals( $expected, $actual );

	}

	public function test_validateSlug() {
		$test = array(
			'/wordpress-5.4.2/sfdc_show_users'   => true,
			'/wordpress-5.4.2/sfdcdd_show_users' => false,
			'/sfdcdd_show_users'                 => false,
			'/sfdc_show_users'                   => true,
			'/sfdc_show_usersdesdf'              => true,
			'sfdc_show_users'                    => true,
		);

		foreach ( $test as $output => $expected ) {

			Functions\when( 'add_query_arg' )->justReturn( $output );

			$actual = $this->instance->validateSlug();

			$this->assertEquals( $expected, $actual );
		}
	}

	public function test_queryUsers() {

		// case 1 - when there is an error
		$obj1 = clone $this->instance;

		$obj1
		->allows( 'callApi' )
		->andReturn(
			array(
				'error'   => 'Could not resolve host: jsonplaceholder.typicdddode.com',
				'info'    => array(),
				'httpres' => '',
			)
		);

		$obj1
		->allows( 'cacheCompInstance' )
		->andReturn( $cacheInstance1 = \Mockery::mock() );

		$cacheInstance1->allows( 'getItem' )->andReturn( $demoString1 = \Mockery::mock() );

		$demoString1->allows( 'isHit' )->andReturn( false );

		$expected = array();
		$actual   = $obj1->queryUsers();
		$this->assertEquals( $expected, $actual );

		// case 2 - first time visiting
		$obj2 = clone $this->instance;
		$obj2
		->allows( 'callApi' )
		->andReturn(
			array(
				'error'   => '',
				'info'    => array(
					array(
						'id'       => 10,
						'name'     => 'Clementina Dubuque',
						'username' => 'Moriah.Stanton',
						'email'    => 'Rey.Padberg@karina.biz',
					),
				),
				'httpres' => 200,
			)
		);

		$obj2
		->allows( 'cacheCompInstance' )
		->andReturn( $cacheInstance2 = \Mockery::mock() );

		$cacheInstance2->allows( 'getItem' )->andReturn( $demoString2 = \Mockery::mock() );

		$demoString2->allows( 'isHit' )->andReturn( false );
		$demoString2->allows( 'set' )->andReturn( null );
		$demoString2->allows( 'expiresAfter' )->andReturn( null );
		$cacheInstance2->allows( 'save' )->andReturn( null );
		$cacheInstance2->allows( 'hasItem' )->andReturn( false );

		$expected = array(
			array(
				'id'       => 10,
				'name'     => 'Clementina Dubuque',
				'username' => 'Moriah.Stanton',
				'email'    => 'Rey.Padberg@karina.biz',
			),
		);
		$actual   = $obj2->queryUsers();
		$this->assertEquals( $expected, $actual );

		//case 3 - getting info from cache
		$expected = array(
			array(
				'id'       => 10,
				'name'     => 'Clementina Dubuque',
				'username' => 'Moriah.Stanton',
				'email'    => 'Rey.Padberg@karina.biz',
			),
		);

		$obj3 = clone $this->instance;
		$obj3
		->allows( 'cacheCompInstance' )
		->andReturn( $cacheInstance3 = \Mockery::mock() );
		$cacheInstance3->allows( 'getItem' )->andReturn( $demoString3 = \Mockery::mock() );
		$demoString3->allows( 'isHit' )->andReturn( true );

		$cacheInstance3->allows( 'hasItem' )->andReturn( true );
		$demoString3->allows( 'get' )->andReturn( $this->base64urlEncode( json_encode( $expected ) ) );
		$actual = $obj3->queryUsers();
		$this->assertEquals( $expected, $actual );

	}
}
