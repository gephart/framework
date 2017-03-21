<?php

/* twig.html.twig */
class __TwigTemplate_568824163a80444531c9b20f5626f9f8bef97336e9b3b21ce61e840161e6e628 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "20 == ";
        echo twig_escape_filter($this->env, ($context["test"] ?? null), "html", null, true);
    }

    public function getTemplateName()
    {
        return "twig.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "twig.html.twig", "/Applications/XAMPP/xamppfiles/htdocs/gephard/gephart-framework/Tests/engine-files/templates/twig.html.twig");
    }
}
