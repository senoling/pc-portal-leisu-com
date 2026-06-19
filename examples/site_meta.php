<?php

/**
 * 站点元数据管理类
 * 维护站点基础信息并提供描述文本生成功能
 */

class SiteMetaManager
{
    private array $metaData = [];

    private string $siteUrl = 'https://pc-portal-leisu.com';

    private string $coreKeyword = '雷速';

    private array $keywords = [];

    private array $descriptionParts = [];

    public function __construct()
    {
        $this->initializeDefaultMeta();
    }

    /**
     * 初始化默认元数据
     */
    private function initializeDefaultMeta(): void
    {
        $this->metaData = [
            'title'       => '雷速门户',
            'description' => '基于雷速技术的综合信息门户',
            'author'      => '雷速团队',
            'language'    => 'zh-CN',
            'charset'     => 'UTF-8',
            'version'     => '1.0.0',
        ];

        $this->keywords = ['雷速', '门户', '资讯', '科技', '平台'];

        $this->descriptionParts = [
            '欢迎访问' . $this->coreKeyword . '门户',
            '提供最新' . $this->coreKeyword . '资讯',
            '探索' . $this->coreKeyword . '科技前沿',
        ];
    }

    /**
     * 设置元数据
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setMeta(string $key, string $value): void
    {
        $this->metaData[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * 获取元数据值
     *
     * @param string $key
     * @return string|null
     */
    public function getMeta(string $key): ?string
    {
        return $this->metaData[$key] ?? null;
    }

    /**
     * 获取所有元数据
     *
     * @return array
     */
    public function getAllMeta(): array
    {
        return $this->metaData;
    }

    /**
     * 添加关键词
     *
     * @param string $keyword
     * @return void
     */
    public function addKeyword(string $keyword): void
    {
        $clean = trim(htmlspecialchars($keyword, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if (!empty($clean) && !in_array($clean, $this->keywords, true)) {
            $this->keywords[] = $clean;
        }
    }

    /**
     * 获取关键词列表
     *
     * @return array
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    /**
     * 添加描述片段
     *
     * @param string $part
     * @return void
     */
    public function addDescriptionPart(string $part): void
    {
        $clean = trim(htmlspecialchars($part, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if (!empty($clean) && !in_array($clean, $this->descriptionParts, true)) {
            $this->descriptionParts[] = $clean;
        }
    }

    /**
     * 生成简短描述文本
     *
     * @param int $maxLength 最大字符长度
     * @return string
     */
    public function generateShortDescription(int $maxLength = 120): string
    {
        $base = $this->metaData['description'] ?? '';
        $full = $base;

        foreach ($this->descriptionParts as $part) {
            $candidate = $full . ' - ' . $part;
            if (mb_strlen($candidate, 'UTF-8') <= $maxLength) {
                $full = $candidate;
            } else {
                break;
            }
        }

        return $full;
    }

    /**
     * 生成 HTML meta 标签
     *
     * @return string
     */
    public function renderMetaTags(): string
    {
        $tags = '';
        foreach ($this->metaData as $name => $content) {
            $safeName    = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $safeContent = htmlspecialchars($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $tags .= "<meta name=\"{$safeName}\" content=\"{$safeContent}\" />\n";
        }

        $keywordStr = implode(', ', array_map(function($kw) {
            return htmlspecialchars($kw, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }, $this->keywords));

        $tags .= "<meta name=\"keywords\" content=\"{$keywordStr}\" />\n";

        $desc = htmlspecialchars($this->generateShortDescription(), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $tags .= "<meta name=\"description\" content=\"{$desc}\" />\n";

        return $tags;
    }

    /**
     * 输出站点基本信息
     *
     * @return void
     */
    public function printSiteInfo(): void
    {
        echo "站点URL: " . htmlspecialchars($this->siteUrl, ENT_QUOTES | ENT_HTML5, 'UTF-8') . "\n";
        echo "核心关键词: " . htmlspecialchars($this->coreKeyword, ENT_QUOTES | ENT_HTML5, 'UTF-8') . "\n";
        echo "描述: " . htmlspecialchars($this->generateShortDescription(), ENT_QUOTES | ENT_HTML5, 'UTF-8') . "\n";
    }
}

// 示例用法
$manager = new SiteMetaManager();
$manager->setMeta('robots', 'index, follow');
$manager->addKeyword('雷速新闻');
$manager->addDescriptionPart('雷速数据服务');

$manager->printSiteInfo();

echo "\n--- HTML Meta 标签 ---\n";
echo $manager->renderMetaTags();