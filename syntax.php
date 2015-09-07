<?php
/**
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 */

if (!defined('DOKU_INC')) {
    define('DOKU_INC', realpath(dirname(__FILE__) . '/../../') . '/');
}

if (!defined('DOKU_PLUGIN')) {
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
}

require_once(DOKU_PLUGIN . 'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_disqus extends DokuWiki_Syntax_Plugin 
{
    /**
     * What kind of syntax are we?
     */
    public function getType()
    {
        return 'substition';
    }

    public function getPType()
    {
        return 'block';
    }

    /**
     * Where to sort in?
     */
    public function getSort()
    {
        return 160;
    }

    /**
     * Connect pattern to lexer
     */
    public function connectTo($mode) 
    {
        $this->Lexer->addSpecialPattern('~~DISQUS~~', $mode, 'plugin_disqus');
    }

    /**
     * Handle the match
     */
    public function handle($match, $state, $pos, $handler)
    {
        return array();
    }

    /**
     * Create output
     */
    public function render($mode, $renderer, $data) 
    {
        if ($mode != 'xhtml') {
            return false;
        }

        $renderer->doc .= $this->_disqus();
        return true;
    }

    protected function _disqus()
    {
        global $ID;
        global $INFO;

        $disqusScript =<<<HTML
<div id="disqus_thread"></div>
<script type="text/javascript">
    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = '%s';
    /* * * DON'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    })();
</script>
<noscript>
    Please enable JavaScript to view the 
    <a href="https://disqus.com/?ref_noscript" rel="nofollow">
        comments powered by Disqus.
    </a>
</noscript>
HTML;

        return sprintf($disqusScript, $this->getConf('shortname'));
    }
}
