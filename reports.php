<?php
require 'includes/connectdb.php';
require_admin();
$pageTitle = 'Course Pal - Reports';
$activePage = 'reports';
$data = [];

if ($pdo) {
    $statement = $pdo->query(
        'SELECT courses.course_id, courses.name AS course_name,
                COUNT(bookings.booking_id) AS total_bookings,
                categories.category_name, categories.category_colour
         FROM courses
         INNER JOIN categories ON courses.category_id = categories.category_id
         LEFT JOIN bookings ON bookings.course_id = courses.course_id
         GROUP BY courses.course_id, courses.name, categories.category_name, categories.category_colour
         ORDER BY total_bookings DESC, courses.date ASC'
    );
    $data = $statement->fetchAll();
}

require 'includes/header.php';
?>
<section>
  <h1>Course Reports</h1>
  <p>Courses are sorted by number of bookings. Bar colours come from the course category records.</p>
  <p>Number of courses: <?php echo e(count($data)); ?></p>

  <div class="chart panel">
    <canvas id="courseReportChart" aria-label="Most popular courses bar chart" role="img"></canvas>
  </div>

  <table>
    <tr>
      <th>Course</th>
      <th>Category</th>
      <th>Bookings</th>
      <th>Class list</th>
    </tr>
    <?php foreach ($data as $row): ?>
      <tr>
        <td><a href="course.php?id=<?php echo e($row['course_id']); ?>"><?php echo e($row['course_name']); ?></a></td>
        <td><?php echo e($row['category_name']); ?></td>
        <td><?php echo e($row['total_bookings']); ?></td>
        <td><a class="btn" href="class-list.php?id=<?php echo e($row['course_id']); ?>">Class list</a></td>
      </tr>
    <?php endforeach; ?>
  </table>
</section>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?php echo json_encode(array_column($data, 'course_name'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
const num_bookings = <?php echo json_encode(array_map('intval', array_column($data, 'total_bookings'))); ?>;
const bar_colours = <?php echo json_encode(array_column($data, 'category_colour'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
const course_links = <?php echo json_encode(array_map(fn($row) => 'course.php?id=' . $row['course_id'], $data), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
const chartCanvas = document.getElementById('courseReportChart');
const chart = new Chart(chartCanvas, {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [{
      label: 'Bookings',
      data: num_bookings,
      backgroundColor: bar_colours,
      borderWidth: 1
    }]
  },
  options: {
    indexAxis: 'y',
    responsive: true,
    plugins: { legend: { display: false } },
    scales: { x: { beginAtZero: true, ticks: { precision: 0 } }, y: { ticks: { color: 'blue', font: { size: 14 } } } },
    onClick: (event, elements, chartInstance) => {
      const points = chartInstance.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
      if (points.length) {
        window.location.href = course_links[points[0].index];
      }
    }
  }
});
</script>
<?php require 'includes/footer.php'; ?>
