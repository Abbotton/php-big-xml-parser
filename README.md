PHP Big XML Parser
=========

PHP Fast XML Parser 是一个PHP扩展库，用于使用 PHP 解析大型 XML 文件。

特性:
- 轻量, 无第三方依赖;
- 灵活, 可以通过回调处理任意解析结果;
- 高性能, 极少的内存占用, 即使解析100MB大小的XML文件,内存占用也可以控制在10MB之内;

### 安装
```shell
composer require abbotton/php-big-xml-parser
```

### 使用

```php
use Abbotton\BigXmlParser\GenericHandler;
use Abbotton\BigXmlParser\Parser;

// 创建handler
$handler = new GenericHandler;

// 设置解析完成回调
$handler->setOnItemParsedCallback(function ($item) {
    // $item变量包含解析的结果.
});

// 设置解析过程回调
$handler->setOnProgressCallback(function ($bytesProcessed, $bytesTotal) {
    // $bytesProcessed 已处理文件大小, 单位(byte)
    // $bytesTotal xml文件总大小, 单位(byte)
    // 可以通过计算获取当前处理进度.
});

// 创建解析器实例
$parser = new Parser($handler);

// 定义您不想包含在结果数组中的标签（可选）
$parser->setIgnoreTags(['root']);

// 为每个项目定义结束标签
// 这用作标记以确定何时处理 XML 项。
// 例如，如果你想从这个 XML 源中提取“值”
// <root>
//    <value>VALUE</value>
//    <value>VALUE</value>
//    <value>VALUE</value>
// </root>
// 你必须调用 $parser->setEndTag('value'),这样才能
// 在 "onItemParsed" 事件中返回每个 <value /> 标签的内容。
$parser->setEndTag('value');

// 另一种情况, 假设您期望这个XML源中的value节点以数组形式返回
// <root>
//    <content>
//        <value>VALUE 1</value>
//        <value>VALUE 2</value>
//        <value>VALUE 3</value>
//    </content>
// </root>
// 则您必须调用$parser->setArrayTags(['value']), 这样才可以得到正确的结果.

// 开始解析
$parser->parse('bigfile.xml');

```

### Thanks
[Alex Oleshkevich](https://github.com/alex-oleshkevich)