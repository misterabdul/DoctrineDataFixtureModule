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

namespace DoctrineDataFixtureModule\Loader;

use Doctrine\Common\DataFixtures\Loader as BaseLoader;

/**
 * Doctrine fixture loader which is ZF2 Service Locator-aware
 * Will inject the service locator instance into all SL-aware fixtures on add
 *
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link    www.doctrine-project.org
 * @author  Adam Lundrigan <adam@lundrigan.ca>
 */
class ServiceLocatorAwareLoader extends BaseLoader
{
    /**
     * @var \Laminas\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param  \Laminas\ServiceManager\ServiceLocatorInterface  $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Add a fixture object instance to the loader.
     *
     * @param  \Doctrine\Common\DataFixtures\FixtureInterface  $fixture
     */
    public function addFixture($fixture)
    {
        // $fixture->setServiceLocator($this->serviceLocator);
        parent::addFixture($fixture);
    }
}
