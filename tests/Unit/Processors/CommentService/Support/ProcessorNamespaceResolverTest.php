<?php

namespace Tests\Unit\Processors\CommentService\Support;

use App\Processors\CommentService\Support\ProcessorClassnameResolver;
use PHPUnit\Framework\TestCase;

class ProcessorNamespaceResolverTest extends TestCase
{
    /**
     * @test
     */
    public function testItWillReturnNamespaceIfRoutingKeyWithChangePrefix()
    {
        $result = ProcessorClassnameResolver::resolve('change.totalComments.all');
        $this->assertEquals('CommentService\\GoodsComments\\UpsertCommentProcessor', $result);
    }

    /**
     * @test
     */
    public function testItWillReturnNamespaceIfRoutingKeyWithCreatePrefix()
    {
        $result = ProcessorClassnameResolver::resolve('create.totalComments.all');
        $this->assertEquals('CommentService\\GoodsComments\\UpsertCommentProcessor', $result);
    }

    /**
     * @test
     */
    public function testItWillReturnNamespaceIfRoutingKeyWithDeletePrefix()
    {
        $result = ProcessorClassnameResolver::resolve('delete.totalComments.all');
        $this->assertEquals('CommentService\\GoodsComments\\DeleteCommentProcessor', $result);
    }
}
