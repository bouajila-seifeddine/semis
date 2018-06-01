<?php
/**
* NOTICE OF LICENSE
*
*   This file is property of Petr Hucik. You may NOT redistribute the code in any way
*   See license.txt for the complete license agreement
*
* @author    Petr Hucik
* @website   https://www.getdatakick.com
* @copyright Petr Hucik <petr@getdatakick.com>
* @license   see license.txt
* @version   2.1.3
*/
namespace Datakick;
require_once(dirname(__FILE__).'/extractor/extractor.php');
require_once(dirname(__FILE__).'/extractor/alias.php');
require_once(dirname(__FILE__).'/extractor/evaluate.php');
require_once(dirname(__FILE__).'/extractor/constant.php');
require_once(dirname(__FILE__).'/extractor/map.php');

require_once(dirname(__FILE__).'/functions/function.php');
require_once(dirname(__FILE__).'/functions/logical.php');
require_once(dirname(__FILE__).'/functions/arithmetic.php');
require_once(dirname(__FILE__).'/functions/identity.php');
require_once(dirname(__FILE__).'/functions/variable.php');
require_once(dirname(__FILE__).'/functions/parameter.php');
require_once(dirname(__FILE__).'/functions/input-value.php');
require_once(dirname(__FILE__).'/functions/to-string.php');
require_once(dirname(__FILE__).'/functions/to-number.php');
require_once(dirname(__FILE__).'/functions/to-date.php');
require_once(dirname(__FILE__).'/functions/is-child-of.php');
require_once(dirname(__FILE__).'/functions/concat.php');
require_once(dirname(__FILE__).'/functions/add.php');
require_once(dirname(__FILE__).'/functions/substract.php');
require_once(dirname(__FILE__).'/functions/times.php');
require_once(dirname(__FILE__).'/functions/divide.php');
require_once(dirname(__FILE__).'/functions/modulo.php');
require_once(dirname(__FILE__).'/functions/or.php');
require_once(dirname(__FILE__).'/functions/and.php');
require_once(dirname(__FILE__).'/functions/equals.php');
require_once(dirname(__FILE__).'/functions/less-than.php');
require_once(dirname(__FILE__).'/functions/greater-than.php');
require_once(dirname(__FILE__).'/functions/not.php');
require_once(dirname(__FILE__).'/functions/now.php');
require_once(dirname(__FILE__).'/functions/format-date.php');
require_once(dirname(__FILE__).'/functions/date-diff.php');
require_once(dirname(__FILE__).'/functions/date-add.php');
require_once(dirname(__FILE__).'/functions/if-else.php');
require_once(dirname(__FILE__).'/functions/decode.php');
require_once(dirname(__FILE__).'/functions/random.php');
require_once(dirname(__FILE__).'/functions/join.php');
require_once(dirname(__FILE__).'/functions/tail.php');
require_once(dirname(__FILE__).'/functions/init.php');
require_once(dirname(__FILE__).'/functions/head.php');
require_once(dirname(__FILE__).'/functions/last.php');
require_once(dirname(__FILE__).'/functions/length.php');
require_once(dirname(__FILE__).'/functions/is-empty.php');
require_once(dirname(__FILE__).'/functions/format-currency.php');
require_once(dirname(__FILE__).'/functions/round.php');
require_once(dirname(__FILE__).'/functions/clean.php');
require_once(dirname(__FILE__).'/functions/allowed-chars.php');
require_once(dirname(__FILE__).'/functions/coalesce.php');
require_once(dirname(__FILE__).'/functions/to-lower-case.php');
require_once(dirname(__FILE__).'/functions/to-upper-case.php');
require_once(dirname(__FILE__).'/functions/starts-with.php');
require_once(dirname(__FILE__).'/functions/ends-with.php');
require_once(dirname(__FILE__).'/functions/contains.php');
require_once(dirname(__FILE__).'/functions/split.php');
require_once(dirname(__FILE__).'/functions/substring.php');
require_once(dirname(__FILE__).'/functions/replace.php');
require_once(dirname(__FILE__).'/functions/replace-reg.php');
require_once(dirname(__FILE__).'/functions/trim.php');
require_once(dirname(__FILE__).'/functions/md5.php');
require_once(dirname(__FILE__).'/functions/sha1.php');
require_once(dirname(__FILE__).'/functions/to-currency.php');
require_once(dirname(__FILE__).'/functions/get-currency-id.php');
require_once(dirname(__FILE__).'/functions/to-unix-timestamp.php');
require_once(dirname(__FILE__).'/functions/ceil.php');
require_once(dirname(__FILE__).'/functions/floor.php');
require_once(dirname(__FILE__).'/functions/replace-accented-chars.php');

class Expressions {
    private $factory;
    private $functions = array();

    public function __construct($factory) {
        $this->factory = $factory;
        $joinFunction = new JoinFunction();
        $lengthFunction = new LengthFunction();
        $formatCurrencyFunction = new FormatCurrencyFunction($factory->getCurrencyFormatUtils());

        $this->register(new IdentityFunction());
        $this->register(new VariableFunction());
        $this->register(new InputValueFunction());
        $this->register(new ToNumberFunction());
        $this->register(new ConcatFunction());
        $this->register(new AddFunction());
        $this->register(new SubstractFunction());
        $this->register(new TimesFunction());
        $this->register(new DivideFunction());
        $this->register(new ModuloFunction());
        $this->register(new OrFunction());
        $this->register(new AndFunction());
        $this->register(new EqualsFunction());
        $this->register(new LessThanFunction());
        $this->register(new GreaterThanFunction());
        $this->register(new NotFunction());
        $this->register(new NowFunction());
        $this->register(new FormatDateFunction());
        $this->register(new IfElseFunction());
        $this->register(new DecodeFunction());
        $this->register(new CoalesceFunction());
        $this->register(new RandomFunction());
        $this->register(new RoundFunction());
        $this->register($joinFunction);
        $this->register(new ParameterFunction());
        $this->register(new IsChildOfFunction($factory));
        $this->register($formatCurrencyFunction);
        $this->register(new ToStringFunction($joinFunction, $formatCurrencyFunction));
        $this->register($lengthFunction);
        $this->register(new TailFunction($lengthFunction));
        $this->register(new InitFunction($lengthFunction));
        $this->register(new HeadFunction());
        $this->register(new LastFunction());
        $this->register(new CleanFunction());
        $this->register(new AllowedCharsFunction());
        $this->register(new DateAddFunction());
        $this->register(new DateDiffFunction());
        $this->register(new ToDateFunction());
        $this->register(new ToLowerCaseFunction());
        $this->register(new ToUpperCaseFunction());
        $this->register(new StartsWithFunction());
        $this->register(new EndsWithFunction());
        $this->register(new ContainsFunction());
        $this->register(new SplitFunction());
        $this->register(new SubstringFunction());
        $this->register(new ReplaceFunction());
        $this->register(new ReplaceRegFunction());
        $this->register(new TrimFunction());
        $this->register(new MD5Function());
        $this->register(new SHA1Function());
        $this->register(new ToCurrencyFunction());
        $this->register(new GetCurrencyIdFunction());
        $this->register(new CeilFunction());
        $this->register(new FloorFunction());
        $this->register(new ToUnixTimestampFunction());
        $this->register(new IsEmptyFunction());
        $this->register(new ReplaceAccentedChars());
    }

    public function register($func) {
        $name = $func->getName();
        $this->functions[$name] = $func;
    }

    public function getFunctionByName($func) {
        if (! isset($this->functions[$func])) {
            throw new \Exception("Invalid function: $func");
        }
        return $this->functions[$func];
    }

    public function getFunction($expr) {
        if (! isset($expr['func']))
            throw new \Exception("Invalid expression: " . print_r($expr, true));
        return $this->getFunctionByName($expr['func']);
    }

    public function expose($query, $expression, Context $context, $formatOutput, $forceSql=false) {
        if (is_array($expression)) {
            $expression = $this->reduceExpression($expression, $context);
            if ($formatOutput)
                $expression = $this->getOutputFormat($expression, $query, $context);
            $this->validateExpression($expression, $query, $context);
            $func = $this->getFunction($expression);
            $isSql = $this->isSqlExpression($expression);
            if (! $isSql && $forceSql)
                throw new \Exception("Expression can't be expressed in SQL");
            $requiresSql = $this->requiresSql($expression);
            $useSql = $forceSql || $requiresSql;
            if ($isSql && $useSql) {
              $sqlExpression = $this->getSqlExpression($query, $expression, $context);
              $type = $this->getType($expression, $query, $context);
              $aliases;
              if (is_array($sqlExpression)) {
                $aliases = array();
                foreach($sqlExpression as $key=>$expr) {
                    $aliases[$key] = $query->exposeExpression($expr);
                }
              } else {
                $aliases = $query->exposeExpression($sqlExpression);
              }
              return new AliasExtractor($aliases, $type);
            }
            $extractor = $this->getExtractor($query, $expression, $context);
            if (! $requiresSql && $this->isDeterministic($expression)) {
                $constant = $extractor->getValue(null);
                return new ConstantExtractor($constant);
            }
            return $extractor;
        }

        return new ConstantExtractor($expression);
    }

    public function exposeCondition($query, $expression, $context) {
        if (is_array($expression)) {
            $expression = $this->reduceExpression($expression, $context);
            $this->validateExpression($expression, $query, $context);
            if (! $this->canExposeAsSql($expression)) {
                throw new \Exception("Expression can't be exposed as SQL");
            }
            $type = $this->getType($expression, $query, $context);
            if ($type !== 'boolean') {
                throw new \Exception("Non-boolean ($type) expression can't be used as condition");
            }
            $sqlCondition = $this->getSqlExpression($query, $expression, $context);
            $query->addCondition($sqlCondition);
            return true;
        }
        throw new \Exception("Cant expose literal as condition");
    }

    public function exposeUpdateField($query, $field, $expression, $transform, $context) {
        if (is_array($expression)) {
            $expression = $this->reduceExpression($expression, $context);
            $this->validateExpression($expression, $query, $context);
            if (! $this->isSqlExpression($expression)) {
                throw new \Exception("Expression can't be exposed as SQL");
            }
            $type = $this->getType($expression, $query, $context);
            $sql = $this->getSqlExpression($query, $expression, $context);
            if (is_array($field)) {
              foreach ($field as $key => $fieldAlias) {
                $value;
                if ($sql && isset($sql[$key])) {
                  $value = $sql[$key];
                } else {
                  $value = $query->encodeLiteral(null, $type);
                }
                $query->addUpdateField($fieldAlias, $value);
              }
            } else {
              $sql = str_replace("<field>", $sql, $transform);
              $query->addUpdateField($field, $sql);
            }
            return true;
        }
        throw new \Exception("Can't expose literal");
    }

    public function getExtractor($query, $expression, $context) {
        if (is_array($expression)) {
            $func = $this->getFunction($expression);
            $args = array();
            $argTypes = array();
            if (isset($expression['args'])) {
              foreach($expression['args'] as $arg) {
                array_push($args, $this->expose($query, $arg, $context, false));
                array_push($argTypes, $this->getType($arg, $query, $context));
              }
            }
            return $func->getExtractor($args, $argTypes, $context, $query, $this->factory);
        }
        throw new \Exception("Can't return extractor for non-expression");
    }

    private function getOutputFormat($expression, $query, $context) {
        $type = $this->getType($expression, $query, $context);
        if ($type === 'string' || $type === 'number' || $type === 'boolean')
            return $expression;
        return array(
            'func' => 'toString',
            'args' => array($expression)
        );
    }

    private function requiresSql($expression) {
        if (is_array($expression) && isset($expression['func'])) {
            $func = $this->getFunction($expression);
            if ($func->requiresSql())
                return true;
            if (isset($expression['args'])) {
                foreach($expression['args'] as $arg) {
                    if ($this->requiresSql($arg))
                        return true;
                }
            }
        }
        return false;
    }

    private function isDeterministic($expression) {
        if (is_array($expression)) {
            $func = $this->getFunction($expression);
            if (! $func->isDeterministic())
                return false;
            if (isset($expression['args'])) {
                foreach($expression['args'] as $arg) {
                    if (! $this->isDeterministic($arg))
                        return false;
                }
            }
        }
        return true;
    }

    function validateExpression($expression, $query, $context) {
      if (is_array($expression)) {
        $args = isset($expression['args']) ? $expression['args'] : array();
        $argTypes = array();
        foreach ($args as $arg) {
          $this->validateExpression($arg, $query, $context);
          array_push($argTypes, $this->getType($arg, $query, $context));
        }
        $this->getFunction($expression)->validateParameters($argTypes);
      }
    }

    public function getType($expression, $query, $context) {
      if (is_array($expression)) {
        $args = isset($expression['args']) ? $expression['args'] : array();
        $argTypes = array();
        foreach ($args as $arg) {
          array_push($argTypes, $this->getType($arg, $query, $context));
        }
        $func = $this->getFunction($expression);
        $type = $func->getType($argTypes, $args, $this->factory->getDictionary(), $query, $context);
        $funcName = $expression['func'];
        if (! $type)
          throw new \Exception("Failed to determine type for $funcName");
        return $type;
      } else {
        return $this->detectType($expression);
      }
    }

    public function getUsedCollections($expression) {
      $ret = array();
      $this->collectUsedCollections($expression, $ret);
      return array_unique($ret);
    }

    private function collectUsedCollections($expression, &$arr) {
      if (is_array($expression)) {
        $funcName = $expression['func'];
        $args = isset($expression['args']) ? $expression['args'] : array();
        if ($funcName === 'variable') {
          array_push($arr, $args[1]);
        } else {
          foreach($args as $arg) {
            $this->collectUsedCollections($arg, $arr);
          }
        }
      }
    }

    public function detectType($obj) {
      if (is_null($obj))
        return 'any';
      if (is_bool($obj))
        return 'boolean';
      if (is_string($obj))
        return 'string';
      if (is_numeric($obj))
        return 'number';
      if (is_a($obj, 'DateTime'))
        return 'datetime';
      if (is_a($obj, "DataKick\Currency"))
        return 'currency';
      throw new \Exception("Failed to detect type for $obj");
    }

    public function getSqlExpression($query, $expression, $context) {
        if (is_array($expression)) {
            $type = $this->getType($expression, $query, $context);
            if (! $this->requiresSql($expression)) {
                $literal = $this->getExtractor($query, $expression, $context)->getValue(null);
                return $query->encodeLiteral($literal, $type);
            }
            $func = $this->getFunction($expression);
            $args = array();
            $argTypes = array();
            if (isset($expression['args'])) {
                foreach($expression['args'] as $arg) {
                    array_push($args, $this->getSqlExpression($query, $arg, $context));
                    array_push($argTypes, $this->getType($arg, $query, $context));
                }
            }
            return $func->getSqlExpression($args, $type, $argTypes, $query, $context);
        }
        return $expression;
    }

    public function isSqlExpression($expression) {
      if (is_array($expression)) {
        $func = $this->getFunction($expression);
        if ($func->supportSql()) {
          if (isset($expression['args'])) {
            $args = $expression['args'];
            foreach ($args as $arg) {
              if (! $this->isSqlExpression($arg))
                return false;
            }
          }
          return true;
        }
        return false;
      }
      return true;
    }

    public function canExposeAsSql($expression) {
      if (is_array($expression)) {
        // if this is valid sql expression, we are good to go
        if ($this->isSqlExpression($expression)) {
          return true;
        }
        // if expr does not requires sql, we can reduce it beforehand
        if (! $this->requiresSql($expression)) {
          return true;
        }

        // if this function itself supports sql, maybe we can reduce its arguments to constants
        $func = $this->getFunction($expression);
        if ($func->supportSql()) {
          if (isset($expression['args'])) {
            foreach ($expression['args'] as $arg) {
              if (!$this->isSqlExpression($arg) && !$this->canReduceToLiteral($arg)) {
                return false;
              }
            }
          }
          return true;
        }

        return false;
      }
      return true;
    }

    public function reduceExpression($expression, $context) {
      if (is_array($expression) && isset($expression['func']) && $expression['func'] != 'identity' && isset($expression['args'])) {
        try {
          return $this->doReduceExpression($expression, $context);
        } catch (\Exception $e) {}
      }
      return $expression;
    }

    private function doReduceExpression($expression, $context) {
      $literals = true;
      $partial = false;
      $argTypes = array();
      $args = array();
      foreach ($expression['args'] as &$arg) {
        $arg = $this->reduceExpression($arg, $context);
        if (is_array($arg) && isset($arg['func'])) {
          if ($arg['func'] == 'identity') {
            $args[] = $arg['args'][0];
            $argTypes[] = $arg['type'];
            $partial = true;
          } else {
            $args[] = null;
            $argTypes[] = null;
            $literals = false;
          }
        } else {
          $partial = true;
          $args[] = $arg;
          $argTypes[] = $this->detectType($arg);
        }
      }
      if ($literals) {
        if ($this->canReduceToLiteral($expression)) {
          $func = $this->getFunction($expression);
          $ret = $func->evaluate($args, $argTypes, $context);
          return array(
            'func' => 'identity',
            'type' => $expression['type'],
            'args' => array($ret)
          );
        }
      } else if ($partial) {
        $ret = $this->getFunction($expression)->partialReduce($expression, $args, $argTypes, $context);
        if (is_array($ret) && isset($ret['func'])) {
          return $ret;
        } else {
          return array(
            'func' => 'identity',
            'type' => $expression['type'],
            'args' => array($ret)
          );
        }
      }
      return $expression;
    }

    public function canReduceToLiteral($expression) {
      if (is_array($expression) && $this->requiresSql($expression)) {
        return false;
      }
      return true;
    }

    public function getFunctions() {
      return array_map(function($func) {
        return $func->getSignature();
      }, $this->functions);
    }
}
