<?php
namespace Algs;

/**
 * p.431
 *
 * 优先级限制下的并行任务调度问题的关键路径方法 (Critical Path Method)
 *
 * **关键路径方法** 的步骤如下: 创建一幅无环加权有向图, 其中包含起点 s 和一个终点 t 且每个任务都
 * 对应着两个顶点 (一个起始顶点和一个结束顶点). 对于每个任务都有一条从它的起始顶点指向结束顶点的边,
 * 边的权重为任务所需的时间. 对于每条优先级限制 v->w, 添加一条从 v 的结束顶点指向 w 的起始顶点的
 * 权重为零的边. 我们还需要为每个任务添加一条从起点指向该任务的起始顶点的权重为零的边以及一条从该任
 * 务的结束顶点到终点的权重为零的边. 这样, 每个任务预计的开始时间即为从起点到它的起始顶点的最长距离.
 */
class CPM
{
    /**
     * % php CPM.php < ../data/jobsPC.txt
     * Start times:
     *    0:   0.0
     *    1:  41.0
     *    2: 123.0
     *    3:  91.0
     *    4:  70.0
     *    5:   0.0
     *    6:  70.0
     *    7:  41.0
     *    8:  91.0
     *    9:  41.0
     * Finish time: 173.0
     */
    public static function main(array $args): void
    {
        $N = StdIn::readInt();
        // 假设任务数为 10, 每个任务需要一个起始顶点, 一个结束顶点, 故需乘以 2
        // 加上起点和终点, 故需加 2
        $G = new EdgeWeightedDigraph(2 * $N + 2);
        $s = 2 * $N; $t = 2 * $N + 1;
        for ($i = 0; $i < $N; $i++) {
            $a = preg_split("/\s+/", StdIn::readLine());
            $duration = (float) $a[0];
            // 任务: 0->10, 1->11, ...
            $G->addEdge(new DirectedEdge($i, $i+$N, $duration));
            // 起点到任务的边
            $G->addEdge(new DirectedEdge($s, $i, 0.0));
            // 任务到终点的边
            $G->addEdge(new DirectedEdge($i+$N, $t, 0.0));
            for ($j = 1; $j < count($a); $j++) {
                $successor = (int) $a[$j];
                // 优先级
                $G->addEdge(new DirectedEdge($i+$N, $successor, 0.0));
            }
        }

        $lp = new AcyclicLP($G, $s);
        StdOut::println("Start times:");
        for ($i = 0; $i < $N; $i++) {
            StdOut::printf("%4d: %5.1f\n", $i, $lp->distTo($i));
        }
        StdOut::printf("Finish time: %5.1f\n", $lp->distTo($t));
    }
}