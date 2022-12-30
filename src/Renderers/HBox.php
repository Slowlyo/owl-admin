<?php

namespace Slowlyo\SlowAdmin\Renderers;

/**
 * Hbox 水平布局渲染器。 文档：https://aisuda.bce.baidu.com/amis/zh-CN/components/hbox
 *
 * @method self disabled($value) 是否禁用
 * @method self disabledOn($value) 是否禁用表达式
 * @method self visibleOn($value) 是否显示表达式
 * @method self static($value) 是否静态展示
 * @method self staticPlaceholder($value) 静态展示空值占位
 * @method self staticSchema($value) 
 * @method self align($value) 水平对齐方式 可选值: left | right | between | center | 
 * @method self className($value) 容器 css 类名
 * @method self onEvent($value) 事件动作配置
 * @method self staticClassName($value) 静态展示表单项类名
 * @method self columns($value) 
 * @method self subFormMode($value) 配置子表单项默认的展示方式。 可选值: normal | inline | horizontal | 
 * @method self subFormHorizontal($value) 如果是水平排版，这个属性可以细化水平排版的左右宽度占比。
 * @method self valign($value) 垂直对齐方式 可选值: top | middle | bottom | between | 
 * @method self visible($value) 是否显示
 * @method self id($value) 组件唯一 id，主要用于日志采集
 * @method self staticInputClassName($value) 静态展示表单项Value类名
 * @method self hiddenOn($value) 是否隐藏表达式
 * @method self staticOn($value) 是否静态展示表达式
 * @method self staticLabelClassName($value) 静态展示表单项Label类名
 * @method self type($value) 指定为each展示类型
 * @method self gap($value) 水平间距 可选值: xs | sm | base | none | md | lg | 
 * @method self hidden($value) 是否隐藏
 */
class HBox extends BaseRenderer
{
    public string $type = 'hbox';
}
