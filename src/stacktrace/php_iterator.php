<?php
/**
 * File containing the ezcDebugPhpStacktraceIterator class.
 *
 * @package Debug
 * @version //autogentag//
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Iterator class to wrap around debug_backtrace() stack traces.
 *
 * This iterator class receives a stack trace generated by debug_backtrace()
 * and unifies it as described in the {@link ezcDebugStacktraceIterator}
 * interface.
 * 
 * @package Debug
 * @version //autogen//
 */
class ezcDebugPhpStacktraceIterator extends ezcDebugStacktraceIterator
{
    /**
     * Unifies a stack element for being returned to the formatter.
     *
     * This method ensures that an element of the stack trace conforms to the
     * format expected by a {@link ezcDebugOutputFormatter}. The format is
     * defined as follows:
     *
     * <code>
     * array(
     *      'file'      => '<fullpathtofile>',
     *      'line'      => <lineno>,
     *      'function'  => '<functionname>',
     *      'class'     => '<classname>',
     *      'params'    => array(
     *          <param_no> => '<paramvalueinfo>',
     *          <param_no> => '<paramvalueinfo>',
     *          <param_no> => '<paramvalueinfo>',
     *          ...
     *      )
     * )
     * </code>
     * 
     * @param mixed $stackElement 
     * @return array As described above.
     */
    protected function unifyStackElement( $stackElement )
    {
        // Not to be set in the unified version
        unset( $stackElement['type'] );
        unset( $stackElement['object'] );

        // Unify args -> params
        $stackElement['params'] = $this->convertArgsToParams( $stackElement['args'] );
        unset( $stackElement['args'] );
        
        return $stackElement;
    }

    /**
     * Returns the arguments of a stack element as string dumps.
     *
     * Returns an array corresponding to the 'params' key of a unified stack
     * element, created from the 'args' ($args) element from an unified one.
     * 
     * @param array $args 
     * @return array
     */
    private function convertArgsToParams( $args )
    {
        $params = array();
        foreach ( $args as $arg )
        {
            $params[] = ezcDebugVariableDumpTool::dumpVariable(
                $arg,
                $this->options->stackTraceMaxData,
                $this->options->stackTraceMaxChildren,
                $this->options->stackTraceMaxDepth
            );
        }
        return $params;
    }
}

?>
