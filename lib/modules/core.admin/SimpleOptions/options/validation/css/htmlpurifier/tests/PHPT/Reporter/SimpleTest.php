<?php

/**
 * Proxies results from PHPT_Reporter to SimpleTest's reporter
 */
class PHPT_Reporter_SimpleTest implements PHPT_Reporter
{

    /** SimpleTest reporter to proxy results to */
    protected $reporter;

    /** @param SimpleTest reporter */
    public function __construct($reporter) {
        $this->reporter = $reporter;
    }

    // TODO: Figure out what the proper calls should be, since we've given
    // each Suite its own UnitTestCase controller

    /**
     * Called when the Reporter is started from a PHPT_Suite
     * @todo Figure out if Suites can be named
     */
    public function onSuiteStart(PHPT_Suite $suite) {
        //$this->reporter->paintGroupStart('PHPT Suite', $suite->count());
    }

    /**
     * Called when the Reporter is finished in a PHPT_Suite
     */
    public function onSuiteEnd(PHPT_Suite $suite) {
        //$this->reporter->paintGroupEnd('PHPT Suite');
    }

    /**
     * Called when a Case is started
     */
    public function onCaseStart(PHPT_Case $case) {
        //$this->reporter->paintCaseStart($case->name);
    }

    /**
     * Called when a Case ends
     */
    public function onCaseEnd(PHPT_Case $case) {
        //$this->reporter->paintCaseEnd($case->name);
    }

    /**
     * Called when a Case runs without Exception
     */
    public function onCasePass(PHPT_Case $case) {
        $this->reporter->paintPass("{$case->name} in {$case->filename}");
    }

    /**
     * Called when a PHPT_Case_VetoException is thrown during a Case's run()
     */
    public function onCaseSkip(PHPT_Case $case, PHPT_Case_VetoException $veto) {
        $this->reporter->paintSkip($veto->getMessage() . ' [' . $case->filename .']');
    }

    /**
     * Called when any Exception other than a PHPT_Case_VetoException is encountered
     * during a Case's run()
     */
    public function onCaseFail(PHPT_Case $case, PHPT_Case_FailureException $failure) {
        $this->reporter->paintFail($failure->getReason());
    }

    public function onParserError(Exception $exception) {
        $this->reporter->paintException($exception);
    }

}

// vim: et sw=4 sts=4
