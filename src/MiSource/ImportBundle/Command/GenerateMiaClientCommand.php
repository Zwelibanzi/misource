<?php
namespace MiSource\ImportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateMiaClientCommand extends ContainerAwareCommand
{

    const TAB = "\t";

    private $_xmlParser = null;

    /**
     * @return null
     */
    public function getXmlParser()
    {
        return $this->_xmlParser;
    }

    /**
     * @param null $xmlParser
     */
    public function setXmlParser($xmlParser)
    {
        $this->_xmlParser = $xmlParser;
    }

    protected function configure()
    {
        $this->setName('miconsole:generate-mia-client')
             ->setDescription('Generates a MIA client for testing and development purposes')
             ->setHelp('Generate a mia client')
             ->addArgument('file', InputArgument::REQUIRED, 'spreadsheet to consume.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('working on this file: ' . $input->getArgument('file'));

         $xmlParser = $this
            ->getContainer()
            ->get('import.parser');

        $this->setXmlParser($xmlParser);

        $xmlParser
            ->setContainer($this->getContainer())
            ->consume($input->getArgument('file'));

        $this->displayRecordsToConsole($output);
    }


    private function displayRecordsToConsole(OutputInterface $consoleOutput) {

        $fields = $this->getXmlParser()->getFields();
        $records = $this->getXmlParser()->getRecords();

        $consoleOutput->writeln('');
        $consoleOutput->writeln('');

        $fields = explode(',', $fields);

        for( $x = 0; $x < count($fields); $x++) {

            $consoleOutput->write(trim($fields[$x]) . $this->makeTabs(3) . ' | ');
        }
        $consoleOutput->writeln('');
        $consoleOutput->writeln('__________________________________________________________________');

        foreach($records as $record) {

            $values = explode(',', $record);
            foreach($values as $value) {
                $consoleOutput->write(trim($value) . $this->makeTabs(3) . ' | ');
            }

            $consoleOutput->writeln('');
            $consoleOutput->writeln('__________________________________________________________________');
        }

        $consoleOutput->writeln('');
        $consoleOutput->writeln('');
        $consoleOutput->writeln('');
    }

    private function makeTabs($numberOfTabs = 1)
    {
        $tabs = self::TAB;

        if ($numberOfTabs > 1) {
            for ($x = 0; $x < $numberOfTabs; $x++) {
                $tabs .= self::TAB;
            }
        }

        return $tabs;
    }
}