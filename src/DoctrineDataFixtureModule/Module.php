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
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace DoctrineDataFixtureModule;

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use DoctrineDataFixtureModule\Command\ImportCommand;
use DoctrineDataFixtureModule\Service\FixtureFactory;
use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\AutoloaderProviderInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;

/**
 * Base module for Doctrine Data Fixture.
 *
 * @license MIT
 * @link    www.doctrine-project.org
 * @author  Martin Shwalbe <martin.shwalbe@gmail.com>
 */
class Module implements
    AutoloaderProviderInterface,
    ServiceProviderInterface,
    ConfigProviderInterface
{
    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Laminas\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                ],
            ],
        ];
    }

    /**
     * @param  \Laminas\ModuleManager\ModuleManager  $e
     */
    public function init($e)
    {
        $events = $e->getEventManager()->getSharedManager();

        // Attach to helper set event and load the entity manager helper.
        $events->attach('doctrine', 'loadCli.post', function (EventInterface $e) {
            $cli = $e->getTarget();

            $sm = $e->getParam('ServiceManager');
            $em = $cli->getHelperSet()->get('em')->getEntityManager();
            $paths = $sm->get('doctrine.configuration.fixtures');

            $importCommand = new ImportCommand($sm);
            $importCommand->setEntityManager($em);
            $importCommand->setPath($paths);
            ConsoleRunner::addCommands($cli);
            $cli->addCommands([$importCommand]);
        });
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'doctrine.configuration.fixtures' => new FixtureFactory('fixtures_default'),
            ],
        ];
    }
}
