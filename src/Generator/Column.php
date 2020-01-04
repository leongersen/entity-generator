<?php

namespace EntityGenerator\Generator;

class Column
{
    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var string|null */
    private $comment;

    /** @var bool */
    private $nullable;

    /** @var bool */
    private $generated;

    /** @var bool */
    private $unsigned;

    /** @var int|null */
    private $length;

    /** @var int|null */
    private $precision;

    /** @var int|null */
    private $scale;

    /** @var bool */
    private $isId = false;

    public function __construct(
        string $name,
        string $type,
        ?string $comment,
        bool $nullable,
        bool $generated,
        bool $unsigned,
        ?int $length,
        ?int $precision,
        ?int $scale
    )
    {
        $this->name = $name;
        $this->type = $type;
        $this->comment = $comment;
        $this->nullable = $nullable;
        $this->generated = $generated;
        $this->unsigned = $unsigned;
        $this->length = $length;
        $this->precision = $precision;
        $this->scale = $scale;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @return int|null
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @return int|null
     */
    public function getPrecision(): ?int
    {
        return $this->precision;
    }

    /**
     * @return int|null
     */
    public function getScale(): ?int
    {
        return $this->scale;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return bool
     */
    public function isGenerated(): bool
    {
        return $this->generated;
    }

    /**
     * @return bool
     */
    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }

    /**
     * @return bool
     */
    public function isId(): bool
    {
        return $this->isId;
    }

    public function markId(): void
    {
        $this->isId = true;
    }
}
