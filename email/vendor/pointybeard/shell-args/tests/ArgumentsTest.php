<?php
use pointybeard\ShellArgs\Lib\ArgumentIterator;

class CommandTest extends \PHPUnit_Framework_TestCase
{
    /** 
     * Create iterator using array of argments and check they have been set correctly.
     */
    public function testValidPassArgsToConstructor()
    {
        $it = new ArgumentIterator(false, [
            '--hithere',
            '-i',
            '-c cheese',
            '--database myDB',
            '-h:local:host'
        ]);

        $this->assertEquals(5, iterator_count($it));
        $this->assertTrue($it->find('hithere')->value());
        $this->assertEquals('cheese', $it->find('c')->value());

        // Since iterator_count() was used, the iterator position should be at the end.
        $this->assertEquals(5, $it->key());

        // Return to start and check the first item is "hithere" with a value of true
        $it->rewind();
        $this->assertEquals(0, $it->key());
        $this->assertEquals('hithere', $it->current()->name());
        $this->assertTrue($it->current()->value());
    }
    
    /** 
     * This test checks to see that an empty array state is handled properly
     */
    public function testEmptyArgumentArray()
    {
        $it = new ArgumentIterator(false, []);
        $this->assertFalse($it->find('hithere'));
        $this->assertEquals(0, $it->count());
    }

    /** 
     * This test set the "ignoreFirst" property to true and check that the first item (file name) is ignored
     */
    public function testValidIgnoreFirstArg()
    {
        $it = new ArgumentIterator(true, [
            '../blah/blah',
            '--hithere',
        ]);
        $this->assertEquals(1, iterator_count($it));
        $this->assertFalse($it->find('../blah/blah'));
    }

    /** 
     * This test seeds the global $argv array with some data to emulate receiving data from the command line
     */
    public function testValidARGV()
    {
        // Seed the $argv array
        global $argv;
        $argv = [
            '../blah/blah',
            '--hithere',
            '-i',
            '-c',
            'cheese',
            '-p:\Users\pointybeard\Sites\shellargs\\',
        ];

        $it = new ArgumentIterator();
        $this->assertEquals(4, iterator_count($it));
        $this->assertTrue($it->find('hithere')->value());
        $this->assertEquals('\Users\pointybeard\Sites\shellargs\\', $it->find('p')->value());
    }

    /** 
     * Give an argument string to the constructor
     */
    public function testValidArgString()
    {
        $it = new ArgumentIterator(false, ['--hithere -i -c cheese']);
        $this->assertEquals(3, iterator_count($it));

        return $it;
    }

    /**
     * This test ensures that a call to a non-existent property will fail
     *
     * Note that the use of __get() magic method in the Argument class is depricated.
     * It has been been superceeded by name() and value() getter methods. This test remains
     * until the depricated code has been removed.
     *
     * @depends testValidArgString
     */
    public function testArgumentMagicMethodGetter(ArgumentIterator $it)
    {
        // Surpress the E_USER_DEPRECATED that gets thrown. We'll check for that later
        $this->assertFalse(@$it->find('hithere')->banana);
        $this->assertEquals('cheese', @$it->find('c')->value);
        $this->assertEquals('c', @$it->find('c')->name);

        // Now check that the PHPUnit_Framework_Error exception is
        // thrown when the E_USER_DEPRECATED error is triggered
        $this->setExpectedException('PHPUnit_Framework_Error');
        $this->assertFalse($it->find('hithere')->banana);
    }
    
    /** 
     * This test checks that the find method behaves correctly when passing an array of
     * argument names.
     */
    public function testValidFindArgumentArray()
    {
        $it = new ArgumentIterator(false, ['--config=/path/to/file --help']);
        $this->assertEquals('/path/to/file', $it->find(['c', 'config'])->value());
        $this->assertTrue($it->find(['usage', 'h', 'help'])->value());

        return $it;
    }

    /**
     * @depends testValidFindArgumentArray
     */
    public function testInvalidFindArgumentArray($it)
    {
        $this->assertFalse($it->find(['f', 'file']));
    }
}
