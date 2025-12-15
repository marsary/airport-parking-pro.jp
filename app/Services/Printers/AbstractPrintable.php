<?php
namespace App\Services\Printers;

abstract class AbstractPrintable
{
    // 印刷設定 (Mpdfのフォーマットなど)
    protected array $config = [];
    protected array $defaultConfig = [];
    // Bladeテンプレートのパス
    protected string $viewPath = '';

    public function __construct(array $config, string $viewPath)
    {
        $this->config = $config;
        $this->viewPath = $viewPath;
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * 印刷に必要なデータを取得する
     * @return array
     */
    abstract public function getData(): array;

    /**
     * 印刷レイアウト設定を取得
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * HTMLコンテンツを生成
     * @return string
     */
    public function render(): string
    {
        return view($this->viewPath, $this->getData())->render();
    }
}
