<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace DoctrineDataFixtureTest\Loader;

use Doctrine\Common\DataFixtures\FixtureInterface;
use DoctrineDataFixtureModule\Loader\ServiceLocatorAwareLoader;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

/**
 * Test Service Locator-aware fixture loader
 *
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link    www.doctrine-project.org
 * @author  Adam Lundrigan <adam@lundrigan.ca>
 */
class ServiceLocatorAwareLoaderTest extends TestCase
{
    /**
     * Ensures that the Service Locator instance passed into the ServiceLocatorAwareLoader
     * actually makes it to the SL-aware fixtures loaded
     */
    public function testLoadingFixtureWhichIsServiceLocatorAware()
    {
        $fixtureClassName = 'DoctrineDataFixtureTest\TestAsset\Fixtures\HasSL\FixtureA';
        $serviceLocator = new ServiceManager([]);

        $loader = new ServiceLocatorAwareLoader($serviceLocator);
        $loader->loadFromDirectory(__DIR__ . '/../TestAsset/Fixtures/HasSL');
        $fixtures = $loader->getFixtures();

        $this->assertArrayHasKey($fixtureClassName, $fixtures);
        $fixture = $fixtures[$fixtureClassName];
        $this->assertInstanceOf(FixtureInterface::class, $fixture);
        $this->assertSame($serviceLocator, $fixture->getServiceLocator());
    }
}
