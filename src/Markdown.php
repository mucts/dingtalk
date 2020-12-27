<?php


namespace MuCTS\DingTalk;


class Markdown
{
    /**
     * 一级标题
     *
     * @param string $h1
     * @return string
     */
    public static function h1(string $h1): string
    {
        return '# ' . $h1;
    }

    /**
     * 二级标题
     *
     * @param string $h2
     * @return string
     */
    public static function h2(string $h2): string
    {
        return '## ' . $h2;
    }

    /**
     * 三级标题
     *
     * @param string $h3
     * @return string
     */
    public static function h3(string $h3): string
    {
        return '### ' . $h3;
    }

    /**
     * 四级标题
     *
     * @param string $h4
     * @return string
     */
    public static function h4(string $h4): string
    {
        return '#### ' . $h4;
    }

    /**
     * 五级标题
     *
     * @param string $h5
     * @return string
     */
    public static function h5(string $h5): string
    {
        return '##### ' . $h5;
    }

    /**
     * 六级标题
     *
     * @param string $h6
     * @return string
     */
    public static function h6(string $h6): string
    {
        return '###### ' . $h6;
    }

    /**
     * 引用
     *
     * @param string $quote
     * @return string
     */
    public static function quote(string $quote): string
    {
        return '> ' . $quote;
    }

    /**
     * 加粗
     *
     * @param string $bold
     * @return string
     */
    public static function bold(string $bold): string
    {
        return '**' . $bold . '**';
    }

    /**
     * 斜体
     *
     * @param string $italic
     * @return string
     */
    public static function italic(string $italic): string
    {
        return '*' . $italic . '*';
    }

    /**
     * 链接
     *
     * @param string $name
     * @param string $url
     * @return string
     */
    public static function url(string $name, string $url): string
    {
        return sprintf('[%s](%s)', $name, $url);
    }

    /**
     * 图片
     *
     * @param string $url
     * @return string
     */
    public static function img(string $url): string
    {
        return sprintf('![](%s)', $url);
    }

    /**
     * 无序列表
     *
     * @param string[] $list
     * @return string
     */
    public static function ul(array $list): string
    {
        $res = [];
        while ($li = array_shift($list)) {
            array_push($res, sprintf('- %s', $li));
        }
        return implode(PHP_EOL, $res);
    }

    /**
     * 有序列表
     *
     * @param array $list
     * @return string
     */
    public static function ol(array $list): string
    {
        $res = [];
        foreach ($list as $i => $ol) {
            array_push($res, sprintf('%d. %s', $i + 1, $ol));
        }
        return implode(PHP_EOL, $res);
    }

    /**
     * 代码
     *
     * @param string $code
     * @param string $language
     * @return string
     */
    public static function code(string $code, string $language = ''): string
    {
        return '```' . $language . PHP_EOL . $code . PHP_EOL . '```';
    }

    /**
     * 横线
     *
     * @return string
     */
    public static function horizontalRule(): string
    {
        return '---';
    }
}