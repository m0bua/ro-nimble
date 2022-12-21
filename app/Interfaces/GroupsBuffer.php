<?php

namespace App\Interfaces;

interface GroupsBuffer
{
    /**
     * Clear group order list
     * @return void
     */
    public function deleteGroups(): void;

    /**
     * Add product order inside group info
     *
     * @param int $groupId
     * @param int $productId
     * @param array $orders
     * @return void
     */
    public function addProduct(int $groupId, int $productId, array $orders): void;

    /**
     * Returns record by group_id & product_id
     *
     * @param int $groupId
     * @param int $productId
     * @return mixed
     */
    public function getGroupOrder(int $groupId, int $productId);
}
