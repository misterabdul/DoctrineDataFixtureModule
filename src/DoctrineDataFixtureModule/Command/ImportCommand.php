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

namespace DoctrineDataFixtureModule\Command;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use DoctrineDataFixtureModule\Loader\ServiceLocatorAwareLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * Command for generate migration classes by comparing your current database schema
 * to your mapping information.
 *
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link    www.doctrine-project.org
 * @since   2.0
 * @author  Jonathan Wage <jonwage@gmail.com>
 */
class ImportCommand extends Command
{
    /**
     * @var string
     */
    const PURGE_MODE_TRUNCATE = 2;

    /**
     * Service Locator instance
     * @var \Laminas\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var array
     */
    protected $paths;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @param  \Laminas\ServiceManager\ServiceLocatorInterface  $serviceLocator
     */
    public function __construct($serviceLocator)
    {
        parent::__construct();
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('data-fixture:import')
            ->setDescription('Import Data Fixtures')
            ->setHelp('The import command Imports data-fixtures')
            ->addOption(
                'append',
                null,
                InputOption::VALUE_NONE,
                'Append data to existing data.'
            )->addOption(
                'purge-with-truncate',
                null,
                InputOption::VALUE_NONE,
                'Truncate tables before inserting data'
            );
    }

    /**
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     */
    public function execute($input, $output)
    {
        $loader = new ServiceLocatorAwareLoader($this->serviceLocator);
        foreach ($this->paths as $value) {
            $loader->loadFromDirectory($value);
        }

        $purger = new ORMPurger();
        if ($input->getOption('purge-with-truncate')) {
            $purger->setPurgeMode(self::PURGE_MODE_TRUNCATE);
        }

        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures(), $input->getOption('append'));
    }

    /**
     * @param  array  $paths
     * @return self
     */
    public function setPath($paths)
    {
        $this->paths = $paths;

        return $this;
    }

    /**
     * @param  \Doctrine\ORM\EntityManagerInterface  $em
     * @return self
     */
    public function setEntityManager($em)
    {
        $this->em = $em;

        return $this;
    }
}
